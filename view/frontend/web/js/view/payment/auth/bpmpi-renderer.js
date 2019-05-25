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

            renderBpmpiData: function (item, element, value) {

                if (element && element.length > 0) {
                    value = element.val();
                }

                if (item) {
                    $('.'+item).val(value);
                }
            }
        }
    }
);