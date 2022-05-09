<?php
/**
 * Unirgy LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unirgy.com/LICENSE-M1.txt
 *
 * @category   Unirgy
 * @package    \Webjump\BraspagPagador
 * @copyright  Copyright (c) 2015-2016 Unirgy LLC (http://www.unirgy.com)
 * @license    http:///www.unirgy.com/LICENSE-M1.txt
 */

namespace Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit\Edit\Tab;

use \Magento\Backend\Block\Widget\Form as WidgetForm;
use \Magento\Config\Model\Config\Source\Website;
use \Magento\Framework\Data\Form as DataForm;
use \Magento\Framework\Event\ManagerInterface;
use \Magento\Framework\Registry;
use \Webjump\BraspagPagador\Helper\Data as HelperData;

/**
 * Class Main
 * @package Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit\Edit\Tab
 * @codeCoverageIgnore
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var HelperData
     */
    protected $_hlp;

    /**
     * @var Website
     */
    protected $storeOptions;

    protected $sourceYesno;

    public function __construct(
        HelperData $helperData,
        \Magento\Store\Model\System\Store $storeOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Source\Yesno $sourceYesno,
        array $data = []
    ) {
        $this->_hlp = $helperData;
        $this->storeOptions = $storeOptions;
        $this->sourceYesno = $sourceYesno;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $cert = $this->_coreRegistry->registry('paymentsplit_data');
        $hlp = $this->_hlp;
        $id = $this->getRequest()->getParam('id');
        $form = $this->_formFactory->create();
        $this->setForm($form);

        $fieldset = $form->addFieldset('shipping_form', [
            'legend'=>__('Payment Split')
        ]);

        $fieldset->addField('store_merchant_id', 'text', [
            'name'      => 'store_merchant_id',
            'label'     => __('Store Merchant ID'),
            'class'     => 'required-entry',
            'required'  => true,
        ]);

        $fieldset->addField('subordinate_merchant_id', 'text', [
            'name'      => 'subordinate_merchant_id',
            'label'     => __('Subordinate Merchant ID'),
            'class'     => 'required-entry',
            'required'  => true,
        ]);

        $fieldset->addField('sales_quote_id', 'text', [
            'name'      => 'sales_quote_id',
            'label'     => __('Sales Quote ID'),
            'class'     => 'required-entry',
            'required'  => false,
        ]);

        $fieldset->addField('sales_order_increment_id', 'text', [
            'name'      => 'sales_order_increment_id',
            'label'     => __('Sales Order Increment ID'),
            'class'     => 'required-entry',
            'required'  => false,
        ]);

        $fieldset->addField('mdr_applied', 'text', [
            'name'      => 'mdr_applied',
            'label'     => __('MDR Applied'),
            'class'     => 'required-entry',
            'required'  => true,
        ]);

        $fieldset->addField('tax_applied', 'text', [
            'name'      => 'tax_applied',
            'label'     => __('Fee Applied'),
            'class'     => 'required-entry',
            'required'  => true,
        ]);

        $fieldset->addField('total_amount', 'text', [
            'name'      => 'total_amount',
            'label'     => __('Total Amount'),
            'class'     => 'required-entry',
            'required'  => true,
        ]);

        $options = $this->storeOptions->toOptionArray();
        array_unshift($options, ['label'=>'All stores', 'value'=>0]);
        $fieldset->addField('store_id', 'select', [
            'name'      => 'store_id',
            'label'     => __('Store'),
            'title'     => __('Store'),
            'required'  => true,
            'values'    => $options,
        ]);

        $this->_eventManager->dispatch('braspag_adminhtml_paymentsplit_edit_prepare_form', ['block'=>$this, 'form'=>$form, 'id'=>$id]);

        if ($this->_coreRegistry->registry('paymentsplit_data')) {
            $form->setValues($this->_coreRegistry->registry('paymentsplit_data')->getData());
        }

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return __('Payment Split');
    }
    public function getTabTitle()
    {
        return __('Payment Split');
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
}
