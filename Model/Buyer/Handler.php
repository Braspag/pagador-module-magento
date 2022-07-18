<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Buyer;

use Braspag\BraspagPagador\Model\AbstractHandler;
use Braspag\Braspag\Pagador\Transaction\BraspagFacade;

class Handler extends AbstractHandler
{

    protected $apiFacade;

    protected $buyer;

    protected $buyerHandler;

    protected $buyerEntity;

    protected $buyerAddress;

    protected $customerFactory;

    protected $customerResource;

    public function __construct(
        BraspagFacade $apiFacade,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\Customer $customerResource
    ) {
        $this->apiFacade = $apiFacade;
        $this->customerFactory = $customerFactory;
        $this->customerResource = $customerResource;
        $this->buyerHandler = $this->apiFacade->getApi()->getObjectFactory()->fetchInstance('Buyer');
        $this->buyerEntity = $this->apiFacade->getApi()->getObjectFactory()->fetchInstance("Buyer\\Entity");
        $this->buyerAddress = $this->apiFacade->getApi()->getObjectFactory()->fetchInstance('Buyer\\Address');
    }
    /**
     * @var Buyer $buyer
     * @var Entity $buyerEntity
     * @var Address $address
    **/

    /**
     * @param \Magento\Quote\Model\Quote $quote
     */
    protected function newBuyer($quote)
    {

        if ($customerId = $quote->getCustomerId()) { // if quote is not guest
            $customer = $this->customerFactory->create();
            $this->customerResource->load($customer, $customerId);
            return $this->buyerFromCustomer($customer, $quote);
        } else {
            return $this->buyerFromQuote($quote);
        }
    }
    /**
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Quote\Model\Quote $quote
     */
    protected function buyerFromCustomer($customer, $quote)
    {
        try {
            $billingAddress = $customer->getDefaultBillingAddress();
            if (!$billingAddress) {
                $billingAddress = $quote->getBillingAddress();
            }
            $this->buyerEntity->setFirstName($customer->getFirstname())
                ->setLastName($customer->getLastname())
                ->setPhoneNumber($billingAddress->getTelephone())
                ->setTaxpayerId($customer->getTaxvat())
                ->setEmail($customer->getEmail());

            $this->buyerAddress->setLine1($billingAddress->getStreetLine(1) . ', ' . $billingAddress->getStreetLine(2))
                ->setCity($billingAddress->getCity())
                ->setState($billingAddress->getRegionCode())
                ->setPostalcode($billingAddress->getPostcode())
                ->setCountrycode($billingAddress->getCountryModel()->getCountryId());

            $this->buyerEntity->setAddress($this->buyerAddress);
            $this->buyer = $this->apiFacade->createCustomer($this->buyerHandler->create($this->buyerEntity));

            return $this->buyer;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * @param  \Magento\Quote\Model\Quote $quote
     */
    protected function buyerFromQuote($quote)
    {
        try {
            $billingAddress = $quote->getBillingAddress();
            $this->buyerEntity->setFirstName($quote->getCustomerFirstname())
                ->setLastName($quote->getCustomerLastname())
                ->setPhoneNumber($billingAddress->getTelephone())
                ->setTaxpayerId($quote->getCustomerTaxvat())
                ->setEmail($quote->getCustomerEmail());

            $this->buyerAddress->setLine1($billingAddress->getStreetLine(1) . ', ' . $billingAddress->getStreetLine(2))
                ->setCity($billingAddress->getCity())
                ->setState($billingAddress->getRegionCode())
                ->setPostalcode($billingAddress->getPostcode())
                ->setCountrycode($billingAddress->getCountryModel()->getCountryId());

            $this->buyerEntity->setAddress($this->buyerAddress);
            $this->buyer = $this->apiFacade->createCustomer($this->buyerHandler->create($this->buyerEntity));

            return $this->buyer;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getBuyer($quote)
    {
        if (is_null($quote)) {
            $quote = $this->getSessionQuote();
        }

        if ($quote->getCustomerTaxvat()) {
            try {
                $this->buyer = $this->apiFacade->fetchCustomer($this->buyerHandler->getByDocument($quote->getCustomerTaxvat()));
                return $this->buyer;
            } catch (\Exception $e) {
                $this->buyer = $this->newBuyer($quote);
                return $this->buyer;
            }
        } else {
            throw new \Exception('CPF/CNPJ do cliente não encontrado');
        }
    }
}