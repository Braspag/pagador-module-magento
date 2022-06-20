<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Gateway\Transaction\Resource\Pix\Send;

use Magento\Quote\Model\Quote\ItemFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\ResourceModel\Quote;
use Magento\Quote\Model\ResourceModel\Quote\Item;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Braspag\BraspagPagador\Model\Buyer\Handler;

class RequestBuilder implements BuilderInterface
{
    /**
     * @var RequestInterface $request
     */
    protected $request;

    /**
     * @var ItemFactory $quoteItemFactory
     */
    protected $quoteItemFactory;

    /**
     * @var Item $resourceItem
     */
    protected $resourceItem;

    /**
     * @var QuoteFactory $quoteFactory
     */
    protected $quoteFactory;

    /**
     * @var Quote $quoteResource
     */
    protected $quoteResource;

    /**
     * @var Handler
     */
    protected $buyerHandler;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * RequestBuilder constructor.
     * @param RequestInterface $request
     * @param ItemFactory $quoteItemFactory
     * @param Item $resourceItem
     * @param LoggerInterface $logger
     * @param QuoteFactory $quoteFactory
     * @param Quote $quoteResource
     * @param Handler $buyerHandler
     */
    public function __construct(
        RequestInterface $request,
        ItemFactory $quoteItemFactory,
        Item $resourceItem,
        QuoteFactory $quoteFactory,
        Quote $quoteResource,
        Handler $buyerHandler
    ) {
        $this->request = $request;
        $this->quoteItemFactory = $quoteItemFactory;
        $this->resourceItem = $resourceItem;
        $this->quoteFactory = $quoteFactory;
        $this->quoteResource = $quoteResource;
        $this->buyerHandler = $buyerHandler;
    }

    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $paymentDataObject = $buildSubject['payment'];
        $orderAdapter = $paymentDataObject->getOrder();
        $quote = $this->getQuoteFromOrderAdapter($orderAdapter);
        $request = $this->request->prepareRequest($quote, $orderAdapter);

        return $request;
    }
    /**
     * @param OrderAdapterInterface $orderAdapter
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuoteFromOrderAdapter(OrderAdapterInterface $orderAdapter): \Magento\Quote\Model\Quote
    {
        /**
         * @var \Magento\Sales\Api\Data\OrderItemInterface $item
         */
        $item = $orderAdapter->getItems()[0];
        $quoteItemId = $item->getQuoteItemId();
        $quoteItem = $this->quoteItemFactory->create();
        $this->resourceItem->load($quoteItem, $quoteItemId);
        $quote = $this->quoteFactory->create();
        $this->quoteResource->load($quote, $quoteItem->getQuoteId());
        return $quote;
    }
}