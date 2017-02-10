<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\AntiFraud\MDD;


class GeneralRequest extends AbstractMDD implements AdapterGeneralInterface
{
    public function getCustomerIsLogged()
    {
        $quote = $this->getConfig()->getQuote();

        return (bool) $quote->getCustomerIsGuest();
    }

    public function getCustomerSinceDays()
    {
        $customer = $this->getConfig()->getCustomer();
        $createdAt = new \DateTime($customer->getCreatedAt());

        $diff = $createdAt->diff(new \DateTime());
        return (int) $diff->days;
    }

    public function getQtyInstallmentsOrder()
    {
        return (int) $this->getPaymentData()->getAdditionalInformation('cc_installments');
    }

    public function getSalesOrderChannel()
    {
        if ($this->getMobileDetect()->isMobile() || $this->getMobileDetect()->isTablet()) {
            return 'Movel';
        }

        return 'Web';
    }

    public function getCouponCode()
    {
        return $this->getConfig()->getQuote()->getCouponCode();
    }

    public function getLastOrderDate()
    {
        $customer = $this->getConfig()->getCustomer();
        $lastOrder = $this->getOrderCollectionFactory()
            ->create($customer->getId())
            ->getLastItem();

        return date('m/d/Y', strtotime($lastOrder->getCreatedAt()));
    }

    public function getQtyTryOrder()
    {

    }

    public function getCustomerFetchSelf()
    {
        if ($this->getConfig()->getQuote()->getShippingMethod() === $this->getConfig()->getFetchSelfShippingMethod()) {
            return true;
        }

        return false;
    }

    public function getVerticalSegment()
    {
        return $this->getConfig()->getVerticalSegment();
    }

    public function getCreditCardBin()
    {
        return substr($this->getPaymentData()->getCcNumber(), 0, 6);
    }

    public function getQtyTryCreditCardNumber()
    {

    }

    public function getEmailFillType()
    {

    }

    public function getCreditCardNumberFillType()
    {
        return (bool) $this->getPaymentData()->getData('cc_number_is_pasted');
    }

    public function getConfirmEmailAddress()
    {
        return (bool) $this->getConfig()->getConfirmEmailAddress();
    }

    public function getHasGiftCard()
    {
        $quote = $this->getConfig()->getQuote();

        return (bool) $quote->getGiftMessageId();
    }
}
