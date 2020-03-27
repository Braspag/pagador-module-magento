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
        'jquery'
    ],
    function(
        $
    ) {
        'use strict';

        return {
            onReady: function () {
                // Evento indicando quando a inicialização do script terminou.
            },
            onSuccess: function (e) {
                // Cartão elegível para autenticação, e portador autenticou com sucesso.

                $('.bpmpi_auth_cavv').val(e.Cavv);
                $('.bpmpi_auth_xid').val(e.Xid);
                $('.bpmpi_auth_eci').val(e.Eci);
                $('.bpmpi_auth_version').val(e.Version);
                $('.bpmpi_auth_reference_id').val(e.ReferenceId);
                $('.bpmpi_auth_failure_type').val(0)
                    .trigger('change');
            },
            onFailure: function (e) {
                // Cartão elegível para autenticação, porém o portador finalizou com falha.

                $('.bpmpi_auth_xid').val(e.Xid);
                $('.bpmpi_auth_eci').val(e.Eci);
                $('.bpmpi_auth_version').val(e.Version);
                $('.bpmpi_auth_reference_id').val(e.ReferenceId);
                $('.bpmpi_auth_failure_type').val(1)
                    .trigger('change');
            },
            onUnenrolled: function (e) {
                // Cartão não elegível para autenticação (não autenticável).
                $('.bpmpi_auth_xid').val(e.Xid);
                $('.bpmpi_auth_eci').val(e.Eci);
                $('.bpmpi_auth_version').val(e.Version);
                $('.bpmpi_auth_reference_id').val(e.ReferenceId);
                $('.bpmpi_auth_failure_type').val(2)
                    .trigger('change');
            },
            onDisabled: function () {
                // Loja não requer autenticação do portador (classe "bpmpi_auth" false -> autenticação desabilitada).
                $('.bpmpi_auth_failure_type').val(3)
                    .trigger('change');
            },
            onError: function (e) {
                // Erro no processo de autenticação.
                var returnCode = e.ReturnCode;

                $('.bpmpi_auth_xid').val(e.Xid);
                $('.bpmpi_auth_eci').val(e.Eci);
                $('.bpmpi_auth_version').val(e.Version);
                $('.bpmpi_auth_reference_id');
                $('.bpmpi_auth_failure_type').val(4)
                    .trigger('change');
            },
            onUnsupportedBrand: function (e) {
                // Bandeira não suportada para autenticação.
                var returnCode = e.ReturnCode;
                var returnMessage = e.ReturnMessage;

                $('.bpmpi_auth_xid').val(e.Xid);
                $('.bpmpi_auth_eci').val(e.Eci);
                $('.bpmpi_auth_version').val(e.Version);
                $('.bpmpi_auth_reference_id');
                $('.bpmpi_auth_failure_type').val(5)
                    .trigger('change');
            },
            Environment: window.checkoutConfig.payment.braspag.isTestEnvironment == '1' ? 'SDB' : 'PRD',
            Debug: window.checkoutConfig.payment.braspag.isTestEnvironment == '1' ? true : false
        };
    }
);