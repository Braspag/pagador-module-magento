<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Resource\MDD;


class GeneralRequest extends AbstractMDD implements AdapterGeneralInterface
{
    public function getCustomerName()
    {
        return $this->helperData
            ->removeSpecialCharacters(
                trim(
                $this->getConfig()->getCustomer()->getFirstname() .
                ' ' .
                $this->getConfig()->getCustomer()->getLastname()
            )
        );
    }

    public function getCustomerIsLogged()
    {
        $quote = $this->getConfig()->getQuote();
        $result = (bool) $quote->getCustomerIsGuest();

        return ($result) ? 'Sim' : 'Não';
    }

    public function getPurchaseByThird()
    {
        $billing = $this->getConfig()->getQuote()->getBillingAddress();
        $shipping = $this->getConfig()->getQuote()->getShippingAddress();

        $result = false;
        if ($billing->getPostcode() !== $shipping->getPostcode()) {
            $result = true;
        }

        return ($result) ? 'Sim' : 'Não';
    }

    public function getSalesOrderChannel()
    {
        if ($this->getMobileDetect()->isMobile() || $this->getMobileDetect()->isTablet()) {
            return 'Movel';
        }

        return 'Web';
    }

    public function getProductCategory()
    {
        $quote = $this->getConfig()->getQuote();
        $result = [];

        foreach ($quote->getAllVisibleItems() as $item) {
            $product = $item->getProduct();
            $result[] = $product->getData($this->getConfig()->getCategoryAttributeCode());
        }

        return implode(', ', $result);
    }

    public function getShippingMethod()
    {
        $quote = $this->getConfig()->getQuote();
        return $quote->getShippingAddress()->getShippingMethod();
    }

    public function getCouponCode()
    {
        return $this->getConfig()->getQuote()->getCouponCode();
    }

    public function getCustomerFetchSelf()
    {
        $result = false;

        if ($this->getConfig()->getQuote()->getShippingMethod() === $this->getConfig()->getFetchSelfShippingMethod()) {
            $result = true;
        }

        return ($result) ? 'Sim' : 'Não';

    }

    public function getStoreCode()
    {
        return $this->getConfig()->getStoreCode();
    }

    public function getHasGiftCard()
    {
        $quote = $this->getConfig()->getQuote();

        $result = false;
        if ((bool) $quote->getGiftMessageId()){
            $result = true;
        }

        return ($result) ? 'Sim' : 'Não';
    }

    /**
     * @return null
     * @codeCoverageIgnore
     */
    public function getSecondPaymentMethod()
    {
        return null;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getPaymentMethodQTY()
    {
        return 1;
    }

    public function getShippingMethodAmount()
    {
        $quote = $this->getConfig()->getQuote();
        return number_format($quote->getShippingAddress()->getShippingAmount(), 2, '.', '');
    }

    /**
     * @return null
     * @codeCoverageIgnore
     */
    public function getSecondPaymentMethodAmount()
    {
        return null;
    }

    public function getSalesOrderAmount()
    {
        $grandTotal = $this->getConfig()->getQuote()->getGrandTotal();
        return number_format($grandTotal, 2, '.', '');
    }

    public function getQtyInstallmentsOrder()
    {
        return (int) $this->getPaymentData()->getAdditionalInformation('cc_installments');
    }

    /**
     * @return String
     * @codeCoverageIgnore
     */
    public function getCardIsPrivateLabel()
    {
        return 'Não';
    }

    public function getCustomerIdentity()
    {
        return $this->getConfig()->getCustomer()->getTaxvat();
    }

    public function getCustomerTelephone()
    {
        $quote = $this->getConfig()->getQuote();
        $result = $quote->getBillingAddress()->getTelephone();

        return (int) preg_replace('/[^0-9]/','', $result);
    }

    public function getStoreIdentity()
    {
        return $this->getConfig()->getStoreIdentity();
    }

    /**
     * @return null
     * @codeCoverageIgnore
     */
    public function getProvider()
    {
        return null;
    }

    /**
     * @return null
     * @codeCoverageIgnore
     */
    public function getCustomerIsRisk()
    {
        return null;
    }

    /**
     * @return null
     * @codeCoverageIgnore
     */
    public function getCustomerIsVIP()
    {
        return null;
    }

}
