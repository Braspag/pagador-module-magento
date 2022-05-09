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

namespace Webjump\BraspagPagador\Block\Adminhtml\PaymentSplit\Grid;

use \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use \Magento\Framework\DataObject;

class Renderer extends AbstractRenderer
{
    /**
     * @var \Magento\Shipping\Model\Config
     */
    protected $_shippingConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Block\Context $context,
        \Magento\Shipping\Model\Config $shippingConfig,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_shippingConfig = $shippingConfig;

        parent::__construct($context, $data);
    }

    public function render(DataObject $row)
    {
        $index = $this->getColumn()->getIndex();
        $value = $row->getData($index);
    }

}
