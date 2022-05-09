<?php

namespace Webjump\BraspagPagador\Block\Form;

use Webjump\BraspagPagador\Api\InstallmentsInterface;

class CreditCard extends \Magento\Payment\Block\Form\Cc
{
    /**
     * @var string
     */
    protected $_template = 'Webjump_BraspagPagador::form/credit-card.phtml';

    /**
     * @var InstallmentsInterface
     */
    private $_installments;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Payment\Model\Config $paymentConfig
     * @param InstallmentsInterface $installments
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Payment\Model\Config $paymentConfig,
        InstallmentsInterface $installments,
        array $data = []
    ) {
        parent::__construct($context, $paymentConfig, $data);
        $this->setInstallments($installments);
    }

    /**
     * Retrieve all installments available
     * @return bool
     * @throws
     * @codecoverageignore
     */
    public function isInstallmentsActive()
    {
        $configData = $this->getMethod()->getConfigData('installments_active');
        if ($configData === null) {
            return true;
        }
        return (bool)$configData;
    }

    /**
     * Retrieve has verification configuration
     * @return mixed
     */
    public function getAllInstallments()
    {
        return $this->getInstallments()->getInstallments();
    }


    /**
     * @return InstallmentsInterface
     */
    private function getInstallments()
    {
        return $this->_installments;
    }

    /**
     * @param InstallmentsInterface $installments
     */
    private function setInstallments($installments)
    {
        $this->_installments = $installments;
    }
}
