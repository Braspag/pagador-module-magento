<?php

namespace Webjump\BraspagPagador\Block\Adminhtml\System\Config\Fieldset;

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class Payment extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    protected function _getFrontendClass($element)
    {
        return parent::_getFrontendClass($element) . ' with-button';
    }

    protected function _getHeaderTitleHtml($element)
    {
        $html = '<div class="config-heading meli" ><div class="heading"><strong id="meli-logo">' . $element->getLegend();
        $html .= '</strong></div>';

        $html .= '<div class="button-container meli-cards"><button type="button"'
            . ' class="meli-payment-btn action-configure button'
            . '" id="' . $element->getHtmlId()
            . '-head" onclick="Fieldset.toggleCollapse(\'' . $element->getHtmlId() . '\', \''
            . $this->getUrl('*/*/state') . '\'); return false;"><span class="state-closed">'
            . __('Configure') . '</span><span class="state-opened">'
            . __('Close') . '</span></button></div></div>';

        return $html;
    }

    protected function _getHeaderCommentHtml($element)
    {
        return '';
    }

    protected function _isCollapseState($element)
    {
        return false;
    }
}
