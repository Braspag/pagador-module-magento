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

namespace Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit;

use \Magento\Backend\Block\Template\Context;
use \Magento\Backend\Block\Widget\Grid as WidgetGrid;
use \Magento\Backend\Helper\Data as HelperData;
use \Magento\Framework\Event\ManagerInterface;
use \Magento\Framework\View\LayoutFactory;
use \Magento\Store\Model\Website;
use Webjump\BraspagPagador\Api\SplitRepositoryInterface;
use \Webjump\BraspagPagador\Helper\Data as BraspagHelperData;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var DropshipHelperData
     */
    protected $_hlp;

    /**
     * @var Store
     */
    protected $storeOptions;

    protected $splitRepository;

    protected $sourceYesno;

    public function __construct(
        BraspagHelperData $helperData,
        LayoutFactory $viewLayoutFactory,
        \Magento\Store\Model\System\Store $storeOptions,
        Context $context,
        HelperData $backendHelper,
        SplitRepositoryInterface $splitRepository,
        \Magento\Config\Model\Config\Source\Yesno $sourceYesno,
        array $data = []
    ) {
        $this->_hlp = $helperData;
        $this->splitRepository = $splitRepository;
        $this->storeOptions = $storeOptions;
        $this->sourceYesno = $sourceYesno;

        parent::__construct($context, $backendHelper, $data);
        $this->setId('paymentSplitGrid');
        $this->setDefaultSort('updated_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $paymentSplit = $this->splitRepository->create();
        $paymentSplitCollection = $paymentSplit->getCollection();

        $this->setCollection($paymentSplitCollection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('subordinate_merchant_id', [
            'header'    => __('Subordinate Merchant ID'),
            'index'     => 'subordinate_merchant_id',
        ]);

        $this->addColumn('store_merchant_id', [
            'header'    => __('Store Merchant ID'),
            'index'     => 'store_merchant_id',
        ]);

        $this->addColumn('sales_quote_id', [
            'header'    => __('Sales Quote ID'),
            'index'     => 'sales_quote_id',
        ]);

        $this->addColumn('sales_order_increment_id', [
            'header'    => __('Sales Order Increment ID'),
            'index'     => 'sales_order_increment_id',
        ]);

        $this->addColumn('mdr_applied', [
            'header'    => __('MDR Applied'),
            'index'     => 'mdr_applied',
            'filter'    => false,
        ]);

        $this->addColumn('tax_applied', [
            'header'    => __('Fee Applied'),
            'index'     => 'tax_applied',
            'filter'    => false,
        ]);

        $this->addColumn('total_amount', [
            'header'    => __('Total Amount'),
            'index'     => 'total_amount',
            'type' => 'currency',
            'filter'    => false,
        ]);

        $this->addColumn('store_id', [
            'header'        => __('Store'),
            'index'         => 'store_id',
            'type'          => 'options',
            'options'       => $this->storeOptions->getStoreOptionHash(),
            'sortable'      => false,
            'filter_condition_callback' => [$this, 'filterStoreCondition'],
        ]);

        $this->addColumn('created_at', [
            'header'    => __('Created At'),
            'index'     => 'created_at',
        ]);

        $this->addColumn('updated_at', [
            'header'    => __('Updated At'),
            'index'     => 'updated_at',
        ]);

        $this->addColumn(
            'action',
            [
                'header'    => __('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => [
                    [
                        'caption' => __('Edit'),
                        'url'     => ['base'=>'braspag/paymentsplit/edit'],
                        'field'   => 'id'
                    ]
                ],
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true
            ]
        );

        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        return parent::_prepareColumns();
    }

    protected function _afterLoadCollection()
    {
        $this->_eventManager->dispatch('braspag_adminhtml_paymentsplit_grid_after_load', ['grid'=>$this]);
        return $this;
    }

    protected function filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addFilter('store_id', $value);
    }


    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('paymentsplit');

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current'=>true]);
    }
}
