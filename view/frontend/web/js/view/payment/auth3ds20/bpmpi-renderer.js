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
            },

            createInputHiddenElement: function(appendToElement, elementName, elementClass, value) {
                if (appendToElement.find("input[value='"+elementName+"']").length == 0) {
                    appendToElement.append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', elementName)
                            .addClass(elementClass)
                    );
                }

                this.renderBpmpiData(elementClass, false, value);
            },
        }
    }
);