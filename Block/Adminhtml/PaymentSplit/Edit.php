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

use \Magento\Backend\Block\Widget\Context;
use \Magento\Backend\Block\Widget\Form\Container;
use \Magento\Framework\Registry;
use Webjump\BraspagPagador\Api\SplitRepositoryInterface;

class Edit extends Container
{
    /**
     * @var \Webjump\BraspagPagador\Helper\Data
     */
    protected $_hlp;

    protected $splitRepository;

    /**
     * @var Registry
     */
    protected $_registry;

    public function __construct(
        \Webjump\BraspagPagador\Helper\Data $dropshipHelper,
        Registry $registry,
        Context $context,
        SplitRepositoryInterface $splitRepository,
        array $data = []
    ) {

        $this->_hlp = $dropshipHelper;
        $this->_registry = $registry;
        $this->splitRepository = $splitRepository;

        parent::__construct($context, $data);

        $this->setData('form_action_url', $this->getUrl('*/*/save', [
            'id' => $this->getRequest()->getParam('id')]
        ));
    }

    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Webjump_BraspagPagador';
        $this->_controller = 'adminhtml_paymentSplit';

        parent::_construct();

        $this->removeButton('delete');

        $this->updateButton('save', 'label', __('Save Payment Split'));

        if ($this->getRequest()->getParam($this->_objectId)) {

            $paymentSplit = $this->splitRepository->create([]);

            $model = $paymentSplit->load($this->getRequest()->getParam($this->_objectId));
            $this->_registry->register('paymentsplit_data', $model);
        }
    }

    public function getHeaderText()
    {
        if ($this->_registry->registry('paymentsplit_data')
            && $this->_registry->registry('paymentsplit_data')->getId()
        ) {
            $data = $this->_registry->registry('paymentsplit_data');

            return __("Edit Payment Split '%1'", $this->escapeHtml($data->getShippingCode()));
        }

        return "";
    }
}
