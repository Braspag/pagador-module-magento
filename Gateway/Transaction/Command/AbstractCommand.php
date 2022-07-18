<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Gateway\Transaction\Command;

use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface as RequestBuilder;
use Magento\Payment\Gateway\Response\HandlerInterface as ResponseHandler;
use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Braspag\Braspag\Pagador\Transaction\FacadeInterface;
use Braspag\BraspagPagador\Gateway\Transaction\Transaction;

abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var FacadeInterface $apiFacade
     */
    protected $apiFacade;

    /**
     * @var RequestBuilder $requestBuilder
     */
    protected $requestBuilder;

    /**
     * @var ValidatorInterface
     */
    protected $requestValidator;

    /**
     * @var ResponseHandler
     */
    protected $responseHandler;

    /**
     * @var ValidatorInterface|null
     */
    protected $responseValidator;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var OrderAdapterInterface
     */
    protected $order;

    /**
     * @var Transaction
     */
    protected $transaction;

    public function __construct(
        FacadeInterface $apiFacade,
        RequestBuilder $requestBuilder,
        ResponseHandler $responseHandler,
        LoggerInterface $logger,
        ValidatorInterface $requestValidator = null,
        ValidatorInterface $responseValidator = null,
        Transaction $transaction
    ) {
        $this->apiFacade = $apiFacade;
        $this->requestBuilder = $requestBuilder;
        $this->responseHandler = $responseHandler;
        $this->logger = $logger;
        $this->requestValidator = $requestValidator;
        $this->responseValidator = $responseValidator;
        $this->transaction = $transaction;
    }

    public function execute(array $commandSubject)
    {
        /**
         * @var PaymentDataObjectInterface $payment
         */
        $payment = $commandSubject['payment'] ?? null;

        if ($payment) {
            $this->order = $payment->getOrder();
        }

        if ($this->getRequestValidator()) {
            $result = $this->getRequestValidator()->validate($commandSubject);
            if (!$result->isValid()) {
                $errorMessage = $result->getFailsDescription();

                throw new LocalizedException(
                    __(reset($errorMessage))
                );
            }
        }
        $request = $this->getRequestBuilder()->build($commandSubject);

        $this->logRequest($request);

        $response = $this->sendRequest($request);

        if ($this->getResponseValidator()) {
            $result = $this->getResponseValidator()->validate(
                array_merge($commandSubject, ['response' => $response])
            );

            if (!$result->isValid()) {
                $errorMessage = $result->getFailsDescription();

                throw new LocalizedException(
                    __(reset($errorMessage))
                );
            }
        }

        $this->getResponseHandler()->handle($commandSubject, $response);
    }

    abstract protected function sendRequest($request);

    /**
     * @return RequestBuilder
     */
    public function getRequestBuilder()
    {
        return $this->requestBuilder;
    }

    /**
     * @return FacadeInterface
     */
    public function getApiFacade(): FacadeInterface
    {
        return $this->apiFacade;
    }

    /**
     * @return ValidatorInterface
     */
    public function getRequestValidator()
    {
        return $this->requestValidator;
    }

    /**
     * @return ResponseHandler
     */
    public function getResponseHandler(): ResponseHandler
    {
        return $this->responseHandler;
    }

    /**
     * @return ValidatorInterface|null
     */
    public function getResponseValidator()
    {
        return $this->responseValidator;
    }

    /**
     * @param \Signativa\Payment\Gateway\Base\Resource\RequestInterface $request
     */
    protected function logRequest($request)
    {
        $request = json_decode(json_encode($request->transactionBody), true);
        if (isset($request['source']['card']['card_number'])) {
            $request['source']['card']['card_number'] = substr($request['source']['card']['card_number'], 0, 4) . '****' . substr($request['source']['card']['card_number'], -4, 4);
        }
        if (isset($request['source']['card']['security_code'])) {
            $request['source']['card']['security_code'] = '***';
        }
        $this->logger->debug('[Signativa Pay Request #]');
        $this->logger->debug(json_encode($request));

        return $this;
    }

    /**
     * @return OrderAdapterInterface
     */
    public function getOrder(): OrderAdapterInterface
    {
        return $this->order;
    }

    /**
     * @param OrderAdapterInterface $order
     * @return AbstractApiCommand
     */
    public function setOrder(OrderAdapterInterface $order): AbstractApiCommand
    {
        $this->order = $order;
        return $this;
    }
}
