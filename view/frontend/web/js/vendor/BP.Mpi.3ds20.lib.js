/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

/*browser:true*/
/*global define*/
define(
    [
        'jquery',
        'Webjump_BraspagPagador/js/vendor/BP.Mpi.3ds20.conf'
    ],
    function(
        $,
        bpMpi3ds20Conf
    ) {
        'use strict';

        var BP = function() {
            function e(e) {
                return $('.'+e).length > 0
            }

            function r(r) {
                // console.log("Class: "+r);
                // console.log("Value: "+$('.'+r).val());
                return e(r) ? $('.'+r).val() : null
            }

            function n(r, n) {
                return e(r) ? document.getElementsByClassName(r)[0].value : n
            }

            function t(e) {
                return e.replace("bpmpi_", "").replace(/\_/g, "")
            }

            function i(e) {
                return /\#/.test(e)
            }

            function o(e, r) {
                return e.replace(/\#/, r)
            }

            function a(e) {
                var r = t(e),
                    n = r.split("#");
                return {
                    enumerable: n[0],
                    field: n[1]
                }
            }

            function s() {
                var n = {};
                for (var s in N) {
                    var p = N[s];
                    if (i(p) === !1) {
                        var c = t(p),
                            d = r(p);
                        d && (n[c] = r(p))
                    } else
                        for (var u = 1, l = o(p, u); e(l);) {
                            var m = a(p);
                            n[m.enumerable] || (n[m.enumerable] = []), d = r(l), n[m.enumerable][u - 1] || (n[m.enumerable][u - 1] = {}), n[m.enumerable][u - 1][m.field] = d, u++, l = o(p, u)
                        }
                }
                return n
            }

            function p() {

                return "undefined" != typeof bpMpi3ds20Conf ? bpMpi3ds20Conf : {
                    Debug: !0,
                    Environment: "PRD"
                }
            }

            function c() {
                return D.Environment || "PRD"
            }

            function d() {
                var e = c(),
                    r = {
                        TST: "https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js",
                        SDB: "https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js",
                        PRD: "https://songbird.cardinalcommerce.com/edge/v1/songbird.js"
                    };
                return r[e]
            }

            function u() {
                return {
                    orderNumber: r("bpmpi_ordernumber"),
                    currency: r("bpmpi_currency"),
                    amount: r("bpmpi_totalamount")
                }
            }

            function l(e) {
                var r = c(),
                    n = {
                        TST: "http://localhost:32142",
                        SDB: "https://mpisandbox.braspag.com.br",
                        PRD: "https://mpi.braspag.com.br"
                    };
                return n[r] + e
            }

            function m(e, r) {
                var n = document.getElementsByTagName("head")[0],
                    t = document.createElement("script");
                t.type = "text/javascript", t.src = e, t.onreadystatechange = r, t.onload = r, n.appendChild(t)
            }

            function _() {
                return "undefined" !== D.Debug ? D.Debug : !1
            }

            function b() {
                _() && console.log.apply(null, arguments)
            }

            function h() {
                return _() ? "verbose" : "off"
            }

            function g(e, r) {
                T = r, b("[MPI]", "Initializing..."), b("[MPI]", "Token =", e), b("[MPI]", "ReferenceId =", T), Cardinal.configure({
                    logging: {
                        debug: h()
                    }
                }), Cardinal.setup("init", {
                    jwt: e
                }), Cardinal.on("payments.setupComplete", function(e) {
                    b("[MPI]", "Setup complete."), b("[MPI]", "SetupCompleteData =", e), M("onReady")
                }), Cardinal.on("payments.validated", function(e) {
                    switch (b("[MPI]", "Payment validated."), b("[MPI]", "ActionCode =", e.ActionCode), b("[MPI]", "Data =", e), e.ActionCode) {
                        case "SUCCESS":
                        case "NOACTION":
                        case "FAILURE":
                            R(e.Payment.ProcessorTransactionId);
                            break;
                        case "ERROR":
                            X.Number = e.ErrorNumber, X.Description = e.ErrorDescription, e.Payment && e.Payment.ProcessorTransactionId ? R(e.Payment.ProcessorTransactionId) : M("onError", {
                                Xid: null,
                                Eci: null,
                                ReturnCode: X.Number,
                                ReturnMessage: X.Description,
                                ReferenceId: null
                            })
                    }
                })
            }

            function f(e, n, t) {

                return new Promise(function (resolve, reject){
                    var i = JSON.stringify(n),
                        o = new XMLHttpRequest;
                    o.onreadystatechange = function() {
                        if (4 === this.readyState)
                            if (200 === this.status) {
                                var e = JSON.parse(o.responseText);
                                resolve(t(e, this))
                            } else M("onError", {
                                Xid: null,
                                Eci: null,
                                ReturnCode: "BP901",
                                ReturnMessage: this.status + " - " + this.statusText,
                                ReferenceId: null
                            })
                    }, o.open(
                        "POST",
                        l(e)),
                        o.setRequestHeader("Content-Type", "application/json"),
                        o.setRequestHeader("Authorization", "Bearer " + r("bpmpi_accesstoken")),
                        o.send(i)
                })
            }

            function C() {
                var e = n("bpmpi_auth", "true");
                return b("[MPI]", "Authentication Enabled =", e), "false" === e ? (M("onDisabled"), !1) : !0
            }

            function E() {
                if (b("[MPI]", "Debug =", _()), b("[MPI]", "Enviroment =", c()), C()) {
                    if (O) return void b("[MPI]", "Resources already loaded...");
                    b("[MPI]", "Loading resources..."), O = !0, m(d(), function() {
                        b("[MPI]", "Cardinal script loaded."), f("/v2/3ds/init", u(), function(e) {
                            g(e.Token, e.ReferenceId)
                        })
                    })
                }
            }

            function y() {
                if (C()) {
                    if (!O) return void b("[MPI]", "Resources not loaded...");
                    return new Promise(function (resolve, reject) {
                        b("[MPI]", "Enrolling..."),
                            Cardinal.trigger(
                                "accountNumber.update",
                                r("bpmpi_cardnumber")),
                            f("/v2/3ds/enroll", s(), function(e) {
                                b("[MPI]", "Enrollment result =", e), e.Version && (k = e.Version[0]);
                                var r = e.Authentication;
                                switch (e.Status) {
                                    case "ENROLLED":
                                        resolve(A(e));
                                        break;
                                    case "VALIDATION_NEEDED":
                                        resolve(R(e.AuthenticationTransactionId));
                                        break;
                                    case "AUTHENTICATION_CHECK_NEEDED":
                                        resolve(P(r));
                                        break;
                                    case "NOT_ENROLLED":
                                        resolve(M("onUnenrolled", {
                                            Xid: r.Xid,
                                            Eci: r.Eci,
                                            Version: k,
                                            ReferenceId: r.DirectoryServerTransactionId
                                        }));
                                        break;
                                    case "FAILED":
                                        resolve(M("onFailure", {
                                            Xid: r.Xid,
                                            Eci: r.Eci || r.EciRaw,
                                            Version: k,
                                            ReferenceId: r.DirectoryServerTransactionId
                                        }));
                                        break;
                                    default:
                                        resolve(M("onError", {
                                            Xid: null,
                                            Eci: null,
                                            ReturnCode: e.ReturnCode,
                                            ReturnMessage: e.ReturnMessage,
                                            ReferenceId: null
                                        }))
                                }
                            })
                    })
                }
            }

            function I(e, r) {
                return e[r] || null
            }

            function v(e) {
                b("[MPI] Building order object...");
                var r = s(),
                    n = {
                        OrderDetails: {
                            TransactionId: e,
                            OrderNumber: r.ordernumber,
                            CurrencyCode: I(r, "currency"),
                            OrderChannel: r.transactionmode || "S"
                        },
                        Consumer: {
                            Account: {
                                AccountNumber: r.cardnumber,
                                ExpirationMonth: r.cardexpirationmonth,
                                ExpirationYear: r.cardexpirationyear
                            },
                            Email1: I(r, "shiptoemail"),
                            Email2: I(r, "billtoemail"),
                            ShippingAddress: {
                                FullName: null,
                                Address1: null,
                                Address2: null,
                                City: null,
                                State: null,
                                PostalCode: null,
                                CountryCode: null,
                                Phone1: null
                            },
                            BillingAddress: {
                                FullName: I(r, "billtocontactname"),
                                Address1: I(r, "billtostreet1"),
                                Address2: I(r, "billtostreet2"),
                                City: I(r, "billtocity"),
                                State: null === I(r, "billtostate") ? null : I(r, "billtostate").toUpperCase(),
                                PostalCode: I(r, "billtozipcode"),
                                CountryCode: I(r, "billtocountry"),
                                Phone1: I(r, "billtophonenumber")
                            }
                        },
                        Cart: []
                    };
                if ("true" === r.shiptosameasbillto) {
                    var t = n.Consumer.BillingAddress;
                    n.Consumer.ShippingAddress.FullName = t.FullName, n.Consumer.ShippingAddress.Address1 = t.Address1, n.Consumer.ShippingAddress.Address2 = t.Address2, n.Consumer.ShippingAddress.City = t.City, n.Consumer.ShippingAddress.State = t.State, n.Consumer.ShippingAddress.PostalCode = t.PostalCode, n.Consumer.ShippingAddress.Phone1 = t.Phone1, n.Consumer.ShippingAddress.CountryCode = t.CountryCode
                } else n.Consumer.ShippingAddress.FullName = I(r, "shiptoaddressee"), n.Consumer.ShippingAddress.Address1 = I(r, "shiptostreet1"), n.Consumer.ShippingAddress.Address2 = I(r, "shiptostreet2"), n.Consumer.ShippingAddress.City = I(r, "shiptocity"), n.Consumer.ShippingAddress.State = null === I(r, "shiptostate") ? null : I(r, "shiptostate").toUpperCase(), n.Consumer.ShippingAddress.PostalCode = I(r, "shiptozipcode"), n.Consumer.ShippingAddress.Phone1 = I(r, "shiptophonenumber"), n.Consumer.ShippingAddress.CountryCode = I(r, "shiptocountry");
                if (r.cart)
                    for (var i in r.cart) n.Cart.push({
                        Name: I(r.cart[i], "name"),
                        Description: I(r.cart[i], "description"),
                        SKU: I(r.cart[i], "sku"),
                        Quantity: I(r.cart[i], "quantity"),
                        Price: I(r.cart[i], "unitprice")
                    });
                return b("[MPI] Order object =", n), n
            }

            function A(e) {
                b("[MPI]", "Showing challenge...");

                return new Promise(function (resolve, reject) {
                    var r = {
                            AcsUrl: e.AcsUrl,
                            Payload: e.Pareq,
                            TransactionId: e.AuthenticationTransactionId
                        },
                        n = v(e.AuthenticationTransactionId);

                    resolve(b("[MPI] Continue object =", r), Cardinal.continue("cca", r, n));
                });
            }

            function P(e) {
                switch (b("[MPI]", "Authentication result =", e), e.Status) {
                    case "AUTHENTICATED":
                        M("onSuccess", {
                            Cavv: e.Cavv,
                            Xid: e.Xid,
                            Eci: e.Eci,
                            Version: e.Version[0],
                            ReferenceId: e.DirectoryServerTransactionId
                        });
                        break;
                    case "UNAVAILABLE":
                        M("onUnenrolled", {
                            Xid: e.Xid,
                            Eci: e.Eci,
                            Version: e.Version[0],
                            ReferenceId: e.DirectoryServerTransactionId
                        });
                        break;
                    case "FAILED":
                        M("onFailure", {
                            Xid: e.Xid,
                            Eci: e.Eci || e.EciRaw,
                            Version: e.Version[0],
                            ReferenceId: e.DirectoryServerTransactionId
                        });
                        break;
                    case "ERROR_OCCURRED":
                        M("onError", {
                            Xid: e.Xid,
                            Eci: e.Eci || e.EciRaw,
                            ReturnCode: e.ReturnCode,
                            ReturnMessage: e.ReturnMessage,
                            ReferenceId: e.DirectoryServerTransactionId
                        });
                        break;
                    default:
                        M("onError", {
                            Xid: e.Xid,
                            Eci: e.Eci || e.EciRaw,
                            ReturnCode: X.HasError() ? X.Number : e.ReturnCode,
                            ReturnMessage: X.HasError() ? X.Description : e.ReturnMessage,
                            ReferenceId: e.DirectoryServerTransactionId
                        })
                }
            }

            function R(e) {
                var r = s();
                r.transactionId = e, b("[MPI]", "Validating..."), f("/v2/3ds/validate", r, function(e) {
                    P(e)
                })
            }

            function S(e) {
                return "function" == typeof D[e]
            }

            function M(e, r) {
                b("[MPI]", "Notifying..."), b("[MPI]", "Event type =", e), b("[MPI]", "Event data =", r || "None"), S(e) && D[e](r)
            }
            var D = p(),
                N = ["bpmpi_transaction_mode", "bpmpi_merchant_url", "bpmpi_merchant_newcustomer", "bpmpi_ordernumber", "bpmpi_currency", "bpmpi_totalamount", "bpmpi_paymentmethod", "bpmpi_installments", "bpmpi_cardnumber", "bpmpi_cardexpirationmonth", "bpmpi_cardexpirationyear", "bpmpi_cardalias", "bpmpi_default_card", "bpmpi_cardaddeddate", "bpmpi_giftcard_amount", "bpmpi_giftcard_currency", "bpmpi_billto_customerid", "bpmpi_billto_contactname", "bpmpi_billto_email", "bpmpi_billto_street1", "bpmpi_billto_street2", "bpmpi_billto_city", "bpmpi_billto_state", "bpmpi_billto_zipcode", "bpmpi_billto_phonenumber", "bpmpi_billto_country", "bpmpi_shipto_sameasbillto", "bpmpi_shipto_addressee", "bpmpi_shipto_email", "bpmpi_shipto_street1", "bpmpi_shipto_street2", "bpmpi_shipto_city", "bpmpi_shipto_state", "bpmpi_shipto_zipcode", "bpmpi_shipto_shippingmethod", "bpmpi_shipto_phonenumber", "bpmpi_shipto_firstusagedate", "bpmpi_shipto_country", "bpmpi_device_ipaddress", "bpmpi_device_#_fingerprint", "bpmpi_device_#_provider", "bpmpi_cart_#_name", "bpmpi_cart_#_description", "bpmpi_cart_#_sku", "bpmpi_cart_#_quantity", "bpmpi_cart_#_unitprice", "bpmpi_order_recurrence", "bpmpi_order_productcode", "bpmpi_order_countlast24hours", "bpmpi_order_countlast6months", "bpmpi_order_countlast1year", "bpmpi_order_cardattemptslast24hours", "bpmpi_order_marketingoptin", "bpmpi_order_marketingsource", "bpmpi_useraccount_guest", "bpmpi_useraccount_createddate", "bpmpi_useraccount_changeddate", "bpmpi_useraccount_passwordchangeddate", "bpmpi_useraccount_authenticationmethod", "bpmpi_useraccount_authenticationprotocol", "bpmpi_useraccount_authenticationtimestamp", "bpmpi_airline_travelleg_#_carrier", "bpmpi_airline_travelleg_#_departuredate", "bpmpi_airline_travelleg_#_origin", "bpmpi_airline_travelleg_#_destination", "bpmpi_airline_passenger_#_name", "bpmpi_airline_passenger_#_ticketprice", "bpmpi_airline_numberofpassengers", "bpmpi_airline_billto_passportcountry", "bpmpi_airline_billto_passportnumber", "bpmpi_mdd1", "bpmpi_mdd2", "bpmpi_mdd3", "bpmpi_mdd4", "bpmpi_mdd5", "bpmpi_auth_notifyonly"],
                T = null,
                k = null,
                X = {
                    Number: null,
                    Description: null,
                    HasError: function() {
                        return null !== this.Number
                    }
                },
                O = !1;
            return {
                Mpi: {
                    load: function() {
                        E()
                    },
                    authenticate: function() {
                        return new Promise(function(resolve, reject){
                            resolve(y());
                        });
                    }
                }
            }
        }();

        return {
            bpmpi_authentication: function () {
                return new Promise(function (resolve, reject){
                    resolve(BP.Mpi.authenticate());
                });
            },
            bpmpi_load : function () {
                BP.Mpi.load()
            }

        };
    }
);


