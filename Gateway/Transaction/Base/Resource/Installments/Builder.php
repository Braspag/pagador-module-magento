<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentFactoryInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\InstallmentsConfigInterface;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\InstallmentInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\State;
use \Magento\Backend\Model\Session\Quote as SessionAdmin;

class Builder implements BuilderInterface
{
    protected $installments = [];

    protected $instalLmentfactory;

    protected $installmentsConfig;

    protected $installmentFactory;

    protected $session;

    /**
     * @var State
     */
    private $state;

    /**
     * @var SessionAdmin
     */
    private $sessionAdmin;

    protected $grandTotal;

    protected $installmentsNumber = 0;

    public function __construct(
        InstallmentFactoryInterface $instalLmentfactory,
        InstallmentsConfigInterface $installmentsConfig,
        Session $session,
        State $state,
        SessionAdmin $sessionAdmin
    ) {
        $this->setInstalLmentfactory($instalLmentfactory);
        $this->setInstallmentsConfig($installmentsConfig);
        $this->setSession($session);
        $this->setSessionAdmin($sessionAdmin);
        $this->setState($state);
    }

    public function build()
    {
        $installmentItems = $this->getInstallmentsNumber();
        for ($i = 1; $i < $installmentItems ; $i++) {

            if (!$this->canProcessInstallment($i)) {
                break;
            }

            $installment = $this->getInstallmentFactory()->create($i, $this->getGrandTotal(), $this->getInstallmentsConfig());
            $this->addInstallment($installment);
        }

        return $this->installments;
    }

    protected function addInstallment(InstallmentInterface $installment)
    {
        $this->installments[] = $installment;

        return $this;
    }

    protected function canProcessInstallment($i)
    {
        $installmentAmount = $this->getGrandTotal() / $i;
        return !($i > 1 && $installmentAmount < $this->getInstallmentsConfig()->getInstallmentMinAmount());
    }

    protected function getInstallmentsNumber()
    {
        if (!$this->installmentsNumber) {
            $this->installmentsNumber = (int) $this->getInstallmentsConfig()->getInstallmentsNumber();
            $this->installmentsNumber++;
        }

        return $this->installmentsNumber;
    }

    protected function getGrandTotal()
    {
        $session = $this->getSession();
        if ($this->getState()->getAreaCode() === 'adminhtml') {
            $session = $this->getSessionAdmin();
        }

        if (!$this->grandTotal) {
            $this->grandTotal = $session->getQuote()->getGrandTotal();
        }

        return $this->grandTotal;
    }

    protected function getInstallmentsConfig()
    {
        return $this->installmentsConfig;
    }

    protected function setInstallmentsConfig(InstallmentsConfigInterface $installmentsConfig)
    {
        $this->installmentsConfig = $installmentsConfig;

        return $this;
    }

    protected function getInstallmentFactory()
    {
        return $this->installmentFactory;
    }

    protected function setInstallmentFactory(InstallmentFactoryInterface $installmentFactory)
    {
        $this->installmentFactory = $installmentFactory;

        return $this;
    }

    protected function getSession()
    {
        return $this->session;
    }

    protected function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return State
     */
    private function getState()
    {
        return $this->state;
    }

    /**
     * @param State $state
     */
    private function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return SessionAdmin
     */
    private function getSessionAdmin()
    {
        return $this->sessionAdmin;
    }

    /**
     * @param SessionAdmin $sessionAdmin
     */
    private function setSessionAdmin($sessionAdmin)
    {
        $this->sessionAdmin = $sessionAdmin;
    }


}
