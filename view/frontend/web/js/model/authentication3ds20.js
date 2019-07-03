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
        "jquery",
    	"Webjump_BraspagPagador/js/vendor/BP.Mpi.3ds20.conf",
    	"Webjump_BraspagPagador/js/vendor/BP.Mpi.3ds20.lib"
    ],
    function(
        $,
        authentication3ds20conf,
        authentication3ds20lib
    ) {
        'use strict';

        return {

            isBpmpiEnabled: function () {
                return window.checkoutConfig.payment.ccform.bpmpi_authentication.active
                    || window.checkoutConfig.payment.dcform.bpmpi_authentication.active;
        	},

            initialize: function () {
            },

            bpmpiLoad: function () {
                authentication3ds20lib.bpmpi_load();
            },

            bpmpiAuthenticate: function () {
                self = this;

                return new Promise(function(resolve, reject){
                    authentication3ds20lib.bpmpi_authentication()
                        .then(function(){

                            var returnData = {
                                'bpmpiAuthFailureType' : $('.bpmpi_auth_failure_type').val(),
                                'bpmpiAuthCavv' : $('.bpmpi_auth_cavv').val(),
                                'bpmpiAuthVersion' : $('.bpmpi_auth_version').val(),
                                'bpmpiAuthXid' : $('.bpmpi_auth_xid').val(),
                                'bpmpiAuthEci' : $('.bpmpi_auth_eci').val(),
                                'bpmpiAuthVersion' : $('.bpmpi_auth_version').val(),
                                'bpmpiAuthReferenceId' : $('.bpmpi_auth_reference_id').val()
                            };

                            resolve(returnData);
                        })
                });
            },

            isBpmpiMasterCardNotifyOnlyEnabled: function() {
                return window.checkoutConfig.payment.ccform.bpmpi_authentication.mastercard_notify_only
                    || window.checkoutConfig.payment.dcform.bpmpi_authentication.mastercard_notify_only;
            },

            disableBpmpi: function() {
                window.checkoutConfig.payment.ccform.bpmpi_authentication.active = false;
                window.checkoutConfig.payment.dcform.bpmpi_authentication.active = false;

                return;
            },

            getConf: function () {
                return authentication3ds20conf;
            }
        };
    }
);
