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

            execute: function () {
                self = this;

                return new Promise(function(resolve, reject){
                    bpmpi_authenticate()
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

            isBpmpiEnabled: function(type) {

                if (type == 'credit'){
                    return window.checkoutConfig.payment.ccform.bpmpi_authenticate.active;
                }

                if (type == 'debit') {
                    return window.checkoutConfig.payment.dcform.bpmpi_authenticate.active;
                }
            }
        }
    }
);