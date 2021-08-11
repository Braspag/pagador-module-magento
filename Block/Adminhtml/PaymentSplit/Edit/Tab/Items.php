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

use \Magento\Backend\Block\Widget\Form;
use \Magento\Framework\Data\Form as DataForm;
use Webjump\BraspagPagador\Api\SplitItemRepositoryInterface;

/**
 * Class Items
 * @package Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit\Edit\Tab
 * @codeCoverageIgnore
 */
class Items extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $_shippingConfig;

    /**
     * @var \Webjump\BraspagPagador\Helper\Data
     */
    protected $_hlp;

    protected $splitItemRepository;

    protected $quoteItem;

    public function __construct(
        \Magento\Shipping\Model\Config $shippingConfig,
        \Webjump\BraspagPagador\Helper\Data $helper,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        SplitItemRepositoryInterface $splitItemRepository,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItem,
        array $data = []
    ) {
        $this->_hlp = $helper;
        $this->_shippingConfig = $shippingConfig;
        $this->splitItemRepository = $splitItemRepository;
        $this->quoteItem = $quoteItem;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $paymentSplit = $this->_coreRegistry->registry('paymentsplit_data');

        if ($paymentSplit) {
            $paymentSplitItem = $this->splitItemRepository->create();
            $paymentSplitItemCollection = $paymentSplitItem->getCollection();
            $paymentSplitItemCollection->addFieldToFilter('split_id', $paymentSplit->getId());
        }

        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset('items_fieldset', ['legend'=>__('Items')]);

        foreach ($paymentSplitItemCollection as $paymentSplitItemKey => $paymentSplitItemModel) {

            if (!empty($paymentSplitItemModel->getSalesQuoteItemId())) {
                $quoteItemFactory = $this->quoteItem->create();
                $quoteItem = $quoteItemFactory->load($paymentSplitItemModel->getSalesQuoteItemId());

                $fieldset->addField('sales_quote_item_label_'.$paymentSplitItemKey, 'label', [
                    'name'      => 'sales_quote_item_label',
                    'label'     => __('SKU ').$quoteItem->getSku(),
                ]);
            }

            $fieldset->addField('sales_quote_item__'.$paymentSplitItemKey, 'text', [
                'name'      => 'sales_quote_item_id',
                'label'     => __('Sales Quote Item ID'),
                'required'  => false,
                'readonly'  => true,
                'value'  => !empty($paymentSplitItemModel->getSalesQuoteItemId()) ? $paymentSplitItemModel->getSalesQuoteItemId() : '',
            ]);

            $fieldset->addField('sales_order_item_id__'.$paymentSplitItemKey, 'text', [
                'name'      => 'sales_order_item_id',
                'label'     => __('Sales Order Item ID'),
                'required'  => false,
                'readonly'  => true,
                'value'  => !empty($paymentSplitItemModel->getSalesOrderItemId()) ? $paymentSplitItemModel->getSalesOrderItemId() : '',
            ]);
        }

        $this->setForm($form);
    }

    public function getTabLabel()
    {
        return __('Items');
    }
    public function getTabTitle()
    {
        return __('Items');
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
