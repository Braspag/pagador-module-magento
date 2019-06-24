var env = getQueryString("env");

function bpmpi_config() {

    return {
        onReady: function () {
            // Evento indicando quando a inicialização do script terminou.
        },
        onSuccess: function (e) {
            // Cartão elegível para autenticação, e portador autenticou com sucesso.

            jQuery('.bpmpi_auth_cavv').val(e.Cavv);
            jQuery('.bpmpi_auth_xid').val(e.Xid);
            jQuery('.bpmpi_auth_eci').val(e.Eci);
            jQuery('.bpmpi_auth_version').val(e.Version);
            jQuery('.bpmpi_auth_reference_id').val(e.ReferenceId);
            jQuery('.bpmpi_auth_failure_type').val(0)
                .trigger('change');
        },
        onFailure: function (e) {
            // Cartão elegível para autenticação, porém o portador finalizou com falha.

            jQuery('.bpmpi_auth_xid').val(e.Xid);
            jQuery('.bpmpi_auth_eci').val(e.Eci);
            jQuery('.bpmpi_auth_version').val(e.Version);
            jQuery('.bpmpi_auth_reference_id').val(e.ReferenceId);
            jQuery('.bpmpi_auth_failure_type').val(1)
                .trigger('change');
        },
        onUnenrolled: function (e) {
            // Cartão não elegível para autenticação (não autenticável).
            jQuery('.bpmpi_auth_xid').val(e.Xid);
            jQuery('.bpmpi_auth_eci').val(e.Eci);
            jQuery('.bpmpi_auth_version').val(e.Version);
            jQuery('.bpmpi_auth_reference_id').val(e.ReferenceId);
            jQuery('.bpmpi_auth_failure_type').val(2)
                .trigger('change');
        },
        onDisabled: function () {
            // Loja não requer autenticação do portador (classe "bpmpi_auth" false -> autenticação desabilitada).
            jQuery('.bpmpi_auth_failure_type').val(3)
                .trigger('change');
        },
        onError: function (e) {
            // Erro no processo de autenticação.
            var returnCode = e.ReturnCode;

            jQuery('.bpmpi_auth_xid').val(e.Xid);
            jQuery('.bpmpi_auth_eci').val(e.Eci);
            jQuery('.bpmpi_auth_version').val(e.Version);
            jQuery('.bpmpi_auth_reference_id');
            jQuery('.bpmpi_auth_failure_type').val(4)
                .trigger('change');
        },
        Environment: "PRD",
        Debug: false
    };
}

function getQueryString(field) {
    var href = window.location.href;
    var reg = new RegExp('[?&]' + field + '=([^&#]*)', 'i');
    var string = reg.exec(href);
    return string ? string[1] : null;
}