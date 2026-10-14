/**
 * @author      Nexaas Team <dev@nexaas.com>
 * @copyright   2026 Nexaas (http://www.nexaas.com)
 * @license     http://www.nexaas.com  Copyright
 *
 * @link        http://www.nexaas.com
 *
 * MPI V3 model — server-to-server flow.
 *
 * Replaces the V2 `authentication3ds20.js` while keeping the SAME public API
 * (isBpmpiEnabled, bpmpiLoad, bpmpiAuthenticate, isBpmpiMasterCardNotifyOnlyEnabled,
 * disableBpmpi) so the credit/debit/voucher renderers do not need per-flow
 * branches — they see V3 identically.
 *
 * Timeline:
 *   1. bpmpiLoad(paymentMethod, cardNumberSelector) — injects mpi.js + mpiHelpers.js
 *      and calls MPI.load() + MPI.init() with the { referenceId, token } coming
 *      from POST /V1/braspag/mpi/session.
 *   2. bpmpiAuthenticate(paymentMethod, cardNumberSelector) — the renderer's
 *      placeOrder hook. Collects card BIN, posts ENROLL. If frictionless
 *      (status=1): populates hidden inputs and resolves. If challenge
 *      (status=2): calls MPI.challenge() and awaits onValidationRequired, then
 *      posts VALIDATE. The paymentMethod arg is REQUIRED for debit/voucher so
 *      the ENROLL body carries PaymentMethod="debit" / "voucher" instead of
 *      defaulting to "credit".
 */
/*browser:true*/
/*global define, MPI, MPIHelpers*/
define(
    [
        'jquery',
        'mage/url',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function ($, mageUrl, quote, fullScreenLoader) {
        'use strict';

        var CDN_HOSTS = {
            sandbox: 'https://mpisandbox.braspag.com.br/Scripts/V3/',
            production: 'https://mpi.braspag.com.br/Scripts/V3/'
        };

        var state = {
            scriptsLoaded: false,
            session: null,
            loadFailed: false,
            mpiBootedOnce: false,
            paymentMethod: null,
            cardNumberSelector: null,
            enrollResult: null,
            enrolledCard: null,
            challengeResolver: null,
            challengeRejecter: null
        };

        function isSandbox() {
            // Wired by CreditCard/Ui/ConfigProvider under bpmpi_authentication.
            return !!(window.checkoutConfig
                && window.checkoutConfig.payment
                && ((window.checkoutConfig.payment.ccform
                        && window.checkoutConfig.payment.ccform.bpmpi_authentication
                        && window.checkoutConfig.payment.ccform.bpmpi_authentication.sandbox)
                    || (window.checkoutConfig.payment.dcform
                        && window.checkoutConfig.payment.dcform.bpmpi_authentication
                        && window.checkoutConfig.payment.dcform.bpmpi_authentication.sandbox)));
        }

        function isDebugActive() {
            // Wired by CreditCard/Ui/ConfigProvider under bpmpi_authentication.
            // Mirrors the admin toggle `Debug Log` that gates the backend
            // var/log/braspag_mpi_v3.log. When OFF, verbose console traces
            // stay silent; console.error/warn stay unconditional so real
            // failures are still visible when a customer reports issues.
            return !!(window.checkoutConfig
                && window.checkoutConfig.payment
                && ((window.checkoutConfig.payment.ccform
                        && window.checkoutConfig.payment.ccform.bpmpi_authentication
                        && window.checkoutConfig.payment.ccform.bpmpi_authentication.debug)
                    || (window.checkoutConfig.payment.dcform
                        && window.checkoutConfig.payment.dcform.bpmpi_authentication
                        && window.checkoutConfig.payment.dcform.bpmpi_authentication.debug)));
        }

        function debugLog() {
            if (!isDebugActive()) { return; }
            // eslint-disable-next-line no-console
            console.log.apply(console, arguments);
        }

        /**
         * Redacts sensitive fields (tokens, Cavv/Xid, PAN) before they hit
         * the browser console. Applied to every payload logged by debugLog.
         * Mutation is done on a shallow copy — the original object used by
         * the flow stays intact.
         */
        function sanitizeForLog(payload) {
            if (!payload || typeof payload !== 'object') { return payload; }
            var out = {};
            for (var key in payload) {
                if (!Object.prototype.hasOwnProperty.call(payload, key)) { continue; }
                var lc = String(key).toLowerCase();
                var value = payload[key];
                if (lc === 'token' || lc === 'jwt' || lc === 'access_token') {
                    out[key] = typeof value === 'string' ? '<' + value.length + ' chars>' : '<redacted>';
                } else if (lc === 'cavv' || lc === 'xid'
                    || lc === 'directoryservertransactionid'
                    || lc === 'directory_server_transaction_id'
                    || lc === 'pareq') {
                    out[key] = '<redacted>';
                } else if (lc === 'cardnumber' || lc === 'card_number' || lc === 'pan') {
                    out[key] = typeof value === 'string' && value.length > 4
                        ? '****' + value.slice(-4)
                        : '<redacted>';
                } else if (value && typeof value === 'object') {
                    out[key] = sanitizeForLog(value);
                } else {
                    out[key] = value;
                }
            }
            return out;
        }

        function scriptBase() {
            return isSandbox() ? CDN_HOSTS.sandbox : CDN_HOSTS.production;
        }

        function loadScript(src) {
            return new Promise(function (resolve, reject) {
                var existing = document.querySelector('script[src="' + src + '"]');
                if (existing) { resolve(); return; }
                var script = document.createElement('script');
                script.src = src;
                script.async = true;
                script.onload = function () { resolve(); };
                script.onerror = function () { reject(new Error('Failed to load ' + src)); };
                document.body.appendChild(script);
            });
        }

        function loadMpiScripts() {
            if (state.scriptsLoaded) { return Promise.resolve(); }
            var base = scriptBase();
            return loadScript(base + 'mpiHelpers.js')
                .then(function () { return loadScript(base + 'mpi.js'); })
                .then(function () { state.scriptsLoaded = true; });
        }

        function startSession() {
            return $.ajax({
                url: mageUrl.build('rest/V1/braspag/mpi/session'),
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ cartId: quote.getQuoteId() })
            });
        }

        // Magento REST converts PHP array keys from camelCase to snake_case
        // when serializing the response body (DataObjectProcessor). So the
        // session PHP returns {referenceId, token, reservedOrderId} but the
        // JS receives {reference_id, token, reserved_order_id}. Normalize
        // here once so the rest of the module reads the expected shape.
        function normalizeSession(raw) {
            if (!raw || typeof raw !== 'object') { return {}; }
            return {
                referenceId: raw.referenceId || raw.reference_id || raw.ReferenceId || '',
                token: raw.token || raw.Token || '',
                reservedOrderId: raw.reservedOrderId || raw.reserved_order_id || raw.ReservedOrderId || ''
            };
        }

        function normalizeAuthentication(raw) {
            if (!raw || typeof raw !== 'object') { return null; }
            return {
                cavv: raw.cavv || raw.Cavv || '',
                xid: raw.xid || raw.Xid || '',
                eci: raw.eci || raw.Eci || '',
                version: raw.version || raw.Version || '',
                directoryServerTransactionId: raw.directoryServerTransactionId
                    || raw.directory_server_transaction_id
                    || raw.DirectoryServerTransactionId
                    || ''
            };
        }

        function normalizeChallenge(raw) {
            if (!raw || typeof raw !== 'object') { return null; }
            return {
                acsUrl: raw.acsUrl || raw.acs_url || raw.AcsUrl || '',
                pareq: raw.pareq || raw.Pareq || '',
                transactionId: raw.transactionId || raw.transaction_id || raw.TransactionId || ''
            };
        }

        function normalizeEnrollResult(raw) {
            if (!raw || typeof raw !== 'object') {
                return { status: 0, reasonCode: '', reasonMessage: '', challenge: null, authentication: null };
            }
            return {
                status: raw.status !== undefined ? Number(raw.status) : 0,
                reasonCode: String(raw.reasonCode || raw.reason_code || ''),
                reasonMessage: String(raw.reasonMessage || raw.reason_message || ''),
                challenge: normalizeChallenge(raw.challenge || raw.Challenge),
                authentication: normalizeAuthentication(raw.authentication || raw.Authentication)
            };
        }

        function buildMpiConfig(session, resolve, reject) {
            return {
                Environment: isSandbox() ? 'SDB' : 'PRD',
                Debug: false,
                onLoadComplete: function () {
                    try {
                        MPI.init(session.referenceId, session.token);
                    } catch (e) {
                        // eslint-disable-next-line no-console
                        console.warn('[Braspag MPI V3] MPI.init threw:', e);
                    }
                },
                onReady: function () {
                    debugLog('[Braspag MPI V3] MPI onReady fired — session Cardinal pronta');
                    resolve();
                },
                onError: function (e) {
                    // eslint-disable-next-line no-console
                    console.error('[Braspag MPI V3] MPI.load onError:', e);
                    // Cardinal fires onError not only on script load failure
                    // but also when the user cancels the challenge popup
                    // (ActionCode ERROR/default without ProcessorTransactionId).
                    // If a challenge Promise is in flight, propagate the
                    // rejection there so bpmpiAuthenticate's error handler
                    // (stopLoader + failureType=4) runs. Otherwise treat it
                    // as a boot failure.
                    if (state.challengeRejecter) {
                        var rejecter = state.challengeRejecter;
                        state.challengeRejecter = null;
                        state.challengeResolver = null;
                        rejecter(new Error(
                            'MPI challenge canceled or errored: '
                            + ((e && e.ReturnMessage) || 'unknown')
                        ));
                        return;
                    }
                    state.loadFailed = true;
                    setHidden('bpmpi_auth_failure_type', 4);
                    reject(new Error('MPI.load failed'));
                },
                onValidationRequired: function (payload) {
                    if (state.challengeResolver) {
                        state.challengeResolver(payload);
                    }
                },
                cardNumberReader: function () {
                    var methodCode = state.paymentMethod === 'debitcard'
                        ? 'braspag_pagador_debitcard'
                        : (state.paymentMethod === 'voucher'
                            ? 'braspag_pagador_voucher'
                            : 'braspag_pagador_creditcard');
                    var el = document.getElementById(methodCode + '_cc_number')
                        || document.querySelector('input[name="payment[cc_number]"]');
                    return el ? String(el.value || '').replace(/\D/g, '') : '';
                }
            };
        }

        function bootMpi(session) {
            if (typeof MPI === 'undefined' || typeof MPIHelpers === 'undefined') {
                return Promise.reject(new Error('MPI scripts did not initialize.'));
            }
            if (!session || !session.token || !session.referenceId) {
                // eslint-disable-next-line no-console
                console.error('[Braspag MPI V3] bootMpi called with empty session:', sanitizeForLog(session));
                return Promise.reject(new Error('MPI session payload missing token/referenceId'));
            }

            return new Promise(function (resolve, reject) {
                var config = buildMpiConfig(session, resolve, reject);
                MPI.load(config);

                // Se `MPI.load` já rodou uma vez, o songbird interno está
                // `_loaded=true` e a segunda chamada NÃO redispara
                // `onLoadComplete` — só sai como no-op. Nesse caso o
                // MPI.init nunca seria chamado com a session nova, e a
                // sessão Cardinal continuaria sendo a antiga (gasta),
                // resultando em HTTP 409 no ENROLL seguinte.
                //
                // NÃO chamamos MPI.init aqui: internamente `_initialize`
                // (mpi.js linha 172-257) faz `Cardinal.on('payments.validated', ...)`
                // toda vez que roda, ou seja, cada re-init acumula listeners
                // (na segunda tentativa, `onError` dispara 2×; na terceira, 3×).
                // Em vez disso, chamamos direto `Cardinal.setup('init', {jwt})`,
                // que reinicializa a session Cardinal com o novo JWT sem
                // registrar listeners duplicados. O `payments.validated`
                // handler do primeiro init continua ativo e captura o novo
                // fluxo normalmente.
                if (state.mpiBootedOnce) {
                    debugLog('[Braspag MPI V3] Cardinal re-setup com nova session (songbird já carregado)');
                    try {
                        Cardinal.setup('init', { jwt: session.token });
                    } catch (e) {
                        // eslint-disable-next-line no-console
                        console.error('[Braspag MPI V3] Cardinal.setup re-init threw:', e);
                        reject(new Error('Cardinal re-setup failed'));
                    }
                }
                state.mpiBootedOnce = true;
            });
        }

        function collectBrowserInfo() {
            if (typeof MPIHelpers === 'undefined' || !MPIHelpers.getBrowserInfo) {
                return {};
            }
            try { return MPIHelpers.getBrowserInfo(); } catch (e) { return {}; }
        }

        function readCard() {
            // Braspag templates (cc-form.html / dc-form.html / vc-form.html)
            // use name attributes `payment[cc_number]`, `payment[cc_exp_month]`,
            // `payment[cc_exp_year]` and ids `<methodCode>_cc_number`,
            // `<methodCode>_expiration`, `<methodCode>_expiration_yr`. The
            // previous selectors (`#credit-card-number`, `.credit-card-select-*`)
            // never matched anything on the DOM, so the payload always went
            // with empty card fields.
            var methodCode = state.paymentMethod === 'debitcard'
                ? 'braspag_pagador_debitcard'
                : (state.paymentMethod === 'voucher'
                    ? 'braspag_pagador_voucher'
                    : 'braspag_pagador_creditcard');

            var cardEl = document.getElementById(methodCode + '_cc_number')
                || document.querySelector('input[name="payment[cc_number]"]');
            var monthEl = document.getElementById(methodCode + '_expiration')
                || document.querySelector('select[name="payment[cc_exp_month]"]');
            var yearEl = document.getElementById(methodCode + '_expiration_yr')
                || document.querySelector('select[name="payment[cc_exp_year]"]');

            var rawNumber = cardEl ? String(cardEl.value || '').replace(/\D/g, '') : '';
            var month = monthEl ? parseInt(monthEl.value, 10) || 0 : 0;
            var year = yearEl ? parseInt(yearEl.value, 10) || 0 : 0;

            debugLog('[Braspag MPI V3] readCard →', {
                methodCode: methodCode,
                cardElFound: !!cardEl,
                monthElFound: !!monthEl,
                yearElFound: !!yearEl,
                cardNumber_len: rawNumber.length,
                month: month,
                year: year
            });

            return {
                CardNumber: rawNumber,
                ExpirationMonth: month,
                ExpirationYear: year,
                // Cielo doc uses lowercase "credit" / "debit" (linha 930/1000).
                PaymentMethod: state.paymentMethod === 'debitcard' ? 'debit' : 'credit'
            };
        }

        function readInstallments() {
            var instEl = document.querySelector('.credit-card-installments-select');
            return instEl ? parseInt(instEl.value, 10) || 1 : 1;
        }

        function setHidden(className, value) {
            $('.' + className).val(value);
        }

        function applyAuthentication(auth, referenceId, failureType) {
            setHidden('bpmpi_auth_failure_type', failureType);
            if (referenceId) { setHidden('bpmpi_auth_reference_id', referenceId); }
            if (auth) {
                setHidden('bpmpi_auth_cavv',    auth.cavv || '');
                setHidden('bpmpi_auth_xid',     auth.xid || '');
                setHidden('bpmpi_auth_eci',     auth.eci || '');
                setHidden('bpmpi_auth_version', auth.version || '');
            }
            // Fire change so the renderers' existing .bpmpi_auth_failure_type
            // change() listener (creditcard.js / debitcard.js / voucher.js)
            // triggers exactly the same handoff it did on V2.
            $('.bpmpi_auth_failure_type').trigger('change');
        }

        function enroll() {
            // Snapshot the card here so VALIDATE (called after the challenge
            // iframe closes) can reuse it. The DOM inputs may be unmounted or
            // cleared by the time VALIDATE runs.
            state.enrolledCard = readCard();
            return $.ajax({
                url: mageUrl.build('rest/V1/braspag/mpi/enroll'),
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    cartId: quote.getQuoteId(),
                    browserInfo: collectBrowserInfo(),
                    card: state.enrolledCard,
                    installments: readInstallments(),
                    paymentMethod: state.paymentMethod
                })
            });
        }

        function runChallenge(challenge) {
            return new Promise(function (resolve, reject) {
                state.challengeResolver = function (payload) {
                    state.challengeResolver = null;
                    resolve(payload && payload.TransactionId ? payload.TransactionId : challenge.transactionId);
                };
                state.challengeRejecter = reject;

                // Use the reservedOrderId (same value the backend sent as
                // OrderNumber in ENROLL) — Cielo rejects mismatches between
                // ENROLL.OrderNumber and the challenge order carrier.
                var orderNumber = state.session && state.session.reservedOrderId
                    ? state.session.reservedOrderId
                    : quote.getQuoteId();

                // withTransaction receives the challenge.transactionId from
                // ENROLL (Cardinal's SDK-side transaction id), NOT the Cielo
                // session ReferenceId. Passing referenceId here causes
                // Cardinal to fail lookup with ReturnCode 1355
                // ("Transaction Lookup Not Successful, Check Transaction Id").
                // See doc line 1602.
                var order = MPIHelpers.getOrderBuilder()
                    .withTransaction(challenge.transactionId)
                    .withOrderNumber(orderNumber)
                    .withCurrency(quote.totals() ? quote.totals().quote_currency_code : 'BRL')
                    .withChannel('S')
                    .build();

                // Cardinal Songbird (Cardinal.continue("cca", ...)) requires
                // PascalCase keys (AcsUrl/Payload/TransactionId). The Cielo
                // MPI doc shows camelCase for MPI.challenge() but the current
                // sandbox mpi.js forwards the object as-is to Cardinal, which
                // then rejects it with "AcsUrl is a required field".
                MPI.challenge({
                    AcsUrl: challenge.acsUrl,
                    Payload: challenge.pareq,
                    TransactionId: challenge.transactionId
                }, order);
            });
        }

        function validate(transactionId) {
            return $.ajax({
                url: mageUrl.build('rest/V1/braspag/mpi/validate'),
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    cartId: quote.getQuoteId(),
                    transactionId: transactionId,
                    // Reuse the snapshot from enroll — the DOM inputs may be
                    // cleared or unmounted after the challenge iframe closes.
                    card: state.enrolledCard || readCard()
                })
            });
        }

        return {

            /**
             * Whether ANY MPI-related toggle is on in admin (credit or debit).
             */
            isBpmpiEnabled: function () {
                return (window.checkoutConfig
                    && window.checkoutConfig.payment
                    && ((window.checkoutConfig.payment.ccform
                            && window.checkoutConfig.payment.ccform.bpmpi_authentication
                            && window.checkoutConfig.payment.ccform.bpmpi_authentication.active)
                        || (window.checkoutConfig.payment.dcform
                            && window.checkoutConfig.payment.dcform.bpmpi_authentication
                            && window.checkoutConfig.payment.dcform.bpmpi_authentication.active)));
            },

            /**
             * @param {string} paymentMethod creditcard / debitcard / voucher
             * @param {string} cardNumberSelector CSS selector of the card # input
             */
            bpmpiLoad: function (paymentMethod, cardNumberSelector) {
                state.paymentMethod = paymentMethod || 'creditcard';
                state.cardNumberSelector = cardNumberSelector || '#credit-card-number';
                if (state.loadFailed) {
                    // Prior boot attempt failed; do not retry silently. The
                    // renderer will get failureType=4 on authenticate.
                    return Promise.resolve();
                }
                fullScreenLoader.startLoader();
                return loadMpiScripts()
                    .then(startSession)
                    .then(function (rawSession) {
                        debugLog('[Braspag MPI V3] raw /mpi/session response:', sanitizeForLog(rawSession));
                        var session = normalizeSession(rawSession);
                        debugLog('[Braspag MPI V3] session normalized:', {
                            referenceId: session.referenceId ? session.referenceId : '(empty)',
                            token_len: session.token ? session.token.length : 0,
                            reservedOrderId: session.reservedOrderId || '(empty)'
                        });
                        state.session = session;
                        return bootMpi(session);
                    })
                    .catch(function (e) {
                        state.loadFailed = true;
                        setHidden('bpmpi_auth_failure_type', 4);
                        // eslint-disable-next-line no-console
                        console.error('[Braspag MPI V3] load failed:', e);
                    })
                    .then(function () { fullScreenLoader.stopLoader(); });
            },

            /**
             * Called from the renderer at placeOrder time. Returns a promise
             * that resolves with { bpmpiAuthFailureType, bpmpiAuthCavv, ... }
             * — the same shape V2 produced, so renderers stay unchanged.
             *
             * @param {string} [paymentMethod] 'creditcard' / 'debitcard' /
             *   'voucher'. Optional for back-compat with the credit renderer;
             *   debit/voucher MUST pass it so the backend ENROLL goes out with
             *   PaymentMethod="debit" instead of defaulting to "credit".
             * @param {string} [cardNumberSelector] CSS selector of the card
             *   number input, optional override.
             */
            bpmpiAuthenticate: function (paymentMethod, cardNumberSelector) {
                var self = this;
                fullScreenLoader.startLoader();

                // Reset o loadFailed antes de tentar de novo: se o clique
                // anterior falhou por sessão gasta, o novo clique deve poder
                // criar session fresh e retentar.
                state.loadFailed = false;

                // Sincroniza o paymentMethod antes do load, senão o fallback
                // interno manda 'creditcard' pro ENROLL mesmo quando o
                // renderer é de débito ou voucher. Mantém o valor atual se
                // nenhum arg explícito veio (compat com renderer de crédito).
                if (paymentMethod) {
                    state.paymentMethod = paymentMethod;
                }
                if (cardNumberSelector) {
                    state.cardNumberSelector = cardNumberSelector;
                }

                // A Cielo trata cada session Cardinal (INIT + MPI.init) como
                // single-use: uma vez que o ENROLL usa aquela session, a
                // próxima chamada com a MESMA session retorna HTTP 409
                // ("operação repetida ou fora de ordem"), mesmo com
                // OrderNumber novo. Por isso, forçamos aqui um novo
                // `startSession` + `MPI.init` a cada tentativa de autenticar.
                debugLog('[Braspag MPI V3] bpmpiAuthenticate — descartando session anterior:', {
                    hadPreviousSession: !!state.session,
                    previousReferenceId: state.session ? state.session.referenceId : null,
                    paymentMethod: state.paymentMethod || 'creditcard'
                });
                state.session = null;
                state.enrollResult = null;
                var readiness = self.bpmpiLoad(state.paymentMethod || 'creditcard', state.cardNumberSelector);

                var stopLoader = function () { fullScreenLoader.stopLoader(); };

                return readiness.then(function () {
                    // Re-check after the lazy boot: if bpmpiLoad just failed
                    // (AUTH 400 / network), do NOT call ENROLL — the backend
                    // would retry the same failing AUTH. Signal failureType=4
                    // and let the admin policy decide.
                    if (state.loadFailed || !state.session) {
                        stopLoader();
                        applyAuthentication(null, '', 4);
                        return Promise.reject(new Error('MPI boot failed; skipping ENROLL'));
                    }

                    // ETAPA 5 do fluxo Cielo (doc linha 85, 780-787):
                    // sincroniza o cartão com a sessão Cardinal ativa antes
                    // do ENROLL. Sem essa chamada, a Cielo retorna HTTP 409
                    // (operação fora de ordem) porque a sessão Cardinal
                    // criada em MPI.init nunca recebeu o accountNumber.update.
                    if (typeof MPI !== 'undefined' && typeof MPI.updateCard === 'function') {
                        try {
                            MPI.updateCard();
                            debugLog('[Braspag MPI V3] MPI.updateCard() called before ENROLL');
                        } catch (e) {
                            // eslint-disable-next-line no-console
                            console.warn('[Braspag MPI V3] MPI.updateCard threw:', e);
                        }
                    }

                    return enroll();
                }).then(function (rawEnrollResult) {
                    debugLog('[Braspag MPI V3] raw /mpi/enroll response:', sanitizeForLog(rawEnrollResult));
                    var enrollResult = normalizeEnrollResult(rawEnrollResult);
                    debugLog('[Braspag MPI V3] enroll normalized:', sanitizeForLog(enrollResult));
                    state.enrollResult = enrollResult;

                    if (enrollResult.status === 1) {
                        // Frictionless success.
                        applyAuthentication(
                            enrollResult.authentication,
                            state.session ? state.session.referenceId : '',
                            0
                        );
                        return;
                    }

                    if (enrollResult.status === 2 && enrollResult.challenge) {
                        return runChallenge(enrollResult.challenge)
                            .then(function (transactionId) { return validate(transactionId); })
                            .then(function (rawValidateResult) {
                                debugLog('[Braspag MPI V3] raw /mpi/validate response:', sanitizeForLog(rawValidateResult));
                                // VALIDATE response goes through the same
                                // Magento REST snake_case conversion.
                                var validateResult = normalizeEnrollResult(rawValidateResult);
                                debugLog('[Braspag MPI V3] validate normalized:', sanitizeForLog(validateResult));
                                var failureType = validateResult.status === 1 ? 0 : 1;
                                applyAuthentication(
                                    validateResult.authentication,
                                    state.session ? state.session.referenceId : '',
                                    failureType
                                );
                            });
                    }

                    // status 0 = failure / unenrolled / other. Reason.Code is
                    // NUMERIC per Cielo doc — 476 = card not enrolled.
                    // failureType map: 0=success, 1=failure, 2=unenrolled,
                    // 3=disabled, 4=error, 5=unsupported_brand.
                    var reason = String(enrollResult.reasonCode || '');
                    var failureType = reason === '476' ? 2 : 1;
                    applyAuthentication(null, state.session ? state.session.referenceId : '', failureType);
                }).then(function () {
                    stopLoader();
                    return {
                        bpmpiAuthFailureType : $('.bpmpi_auth_failure_type').val(),
                        bpmpiAuthCavv        : $('.bpmpi_auth_cavv').val(),
                        bpmpiAuthVersion     : $('.bpmpi_auth_version').val(),
                        bpmpiAuthXid         : $('.bpmpi_auth_xid').val(),
                        bpmpiAuthEci         : $('.bpmpi_auth_eci').val(),
                        bpmpiAuthReferenceId : $('.bpmpi_auth_reference_id').val()
                    };
                }, function (err) {
                    stopLoader();
                    // eslint-disable-next-line no-console
                    console.error('[Braspag MPI V3] authenticate failed:', err);
                    setHidden('bpmpi_auth_failure_type', 4);
                    $('.bpmpi_auth_failure_type').trigger('change');
                    return {
                        bpmpiAuthFailureType : $('.bpmpi_auth_failure_type').val(),
                        bpmpiAuthCavv        : '',
                        bpmpiAuthVersion     : '',
                        bpmpiAuthXid         : '',
                        bpmpiAuthEci         : '',
                        bpmpiAuthReferenceId : ''
                    };
                });
            },

            isBpmpiMasterCardNotifyOnlyEnabled: function () {
                return (window.checkoutConfig
                    && window.checkoutConfig.payment
                    && ((window.checkoutConfig.payment.ccform
                            && window.checkoutConfig.payment.ccform.bpmpi_authentication
                            && window.checkoutConfig.payment.ccform.bpmpi_authentication.mastercard_notify_only)
                        || (window.checkoutConfig.payment.dcform
                            && window.checkoutConfig.payment.dcform.bpmpi_authentication
                            && window.checkoutConfig.payment.dcform.bpmpi_authentication.mastercard_notify_only)));
            },

            disableBpmpi: function () {
                if (window.checkoutConfig.payment.ccform && window.checkoutConfig.payment.ccform.bpmpi_authentication) {
                    window.checkoutConfig.payment.ccform.bpmpi_authentication.active = false;
                }
                if (window.checkoutConfig.payment.dcform && window.checkoutConfig.payment.dcform.bpmpi_authentication) {
                    window.checkoutConfig.payment.dcform.bpmpi_authentication.active = false;
                }
            },

            getConf: function () { return {}; }
        };
    }
);
