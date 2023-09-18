<?php

namespace Braspag\BraspagPagador\Gateway\Transaction\Base\Command;

use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\CommandInterface;
use Braspag\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
use Magento\Payment\Gateway\Request\BuilderInterface as RequestBuilder;
use Magento\Payment\Gateway\Response\HandlerInterface as ResponseHandler;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Request\HandlerInterface as RequestHandler;
use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Magento\Payment\Gateway\Command\CommandException;

/**
 * Braspag Transaction Abstract Api Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.     com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
abstract class AbstractApiCommand implements CommandInterface
{
    protected $api;

    protected $requestBuilder;

    protected $responseHandler;

    protected $requestHandler;

    protected $requestValidator;

    protected $responseValidator;

    protected $twoCardResponse;

    public function __construct(
        BraspagApi $api,
        RequestBuilder $requestBuilder,
        RequestHandler $requestHandler,
        ResponseHandler $responseHandler,
        ValidatorInterface $requestValidator = null,
        ValidatorInterface $responseValidator = null
    ) {
        $this->setApi($api);
        $this->setRequestBuilder($requestBuilder);
        $this->setResponseHandler($responseHandler);
        $this->setRequestHandler($requestHandler);
        $this->setRequestValidator($requestValidator);
        $this->setResponseValidator($responseValidator);
        $this->twoCardResponse = [];
    }

    public function execute(array $commandSubject)
    {
       
        $hasCaptureTwoCard = $this->hasCaputureTwoCard($commandSubject);
        
        if ($this->getRequestBuilder()->hasCardTwo() || $hasCaptureTwoCard) {
          
            if($hasCaptureTwoCard){
               $this->prepareRequest($commandSubject, false, 'capture_two_card');
            }else {
                $this->prepareRequest($commandSubject, false, 'two_card');
            }
           
            $this->prepareRequest($commandSubject, true, 'primary_card');
          
        }else {
             $this->prepareRequest($commandSubject, true, null);
        }

        return $this;
    }

    protected function prepareRequest($commandSubject, $handle, $typeCard = '')
    {

        $request = $this->getRequestBuilder()->build($commandSubject, $typeCard);

        if ($this->getRequestValidator()) {
            $result = $this->getRequestValidator()->validate(
                array_merge($commandSubject, ['request' => $request])
            );

            if (!$result->isValid()) {
                $errorMessage = $result->getFailsDescription();

                throw new LocalizedException(
                    __(reset($errorMessage))
                );
            }
        }


        //for order cancel Pix
        if ( $commandSubject['payment']->getPayment()->getOrder()->getStatus() == 'pending' && $commandSubject['payment']->getPayment()->getMethod() == 'braspag_pagador_pix') 
            {
                return $this;
            }
         

        $response = $this->sendRequest($request);

        if ($this->getResponseValidator()) {
            $result = $this->getResponseValidator()->validate(
                array_merge($commandSubject, ['response' => $response])
            );

            if (!$result->isValid()) {
                $errorMessage = $result->getFailsDescription();

                if($typeCard == 'primary_card')
                    $this->getRequestBuilder()->buildCardTwoVoid();
                
                throw new LocalizedException(
                    __(reset($errorMessage))
                );
            }
        }

        if($typeCard == 'two_card')
         $this->getRequestBuilder()->setResponse($response);
       

       if($handle)
        $this->getResponseHandler()->handle($commandSubject, ['response' => $response]);

        return $response;
    }

    abstract protected function sendRequest($request);

    protected function hasCaputureTwoCard($commandSubject)
    {
       $hasCapture =  $commandSubject['payment']->getPayment()->getAdditionalInformation('two_card_paymentId');

       if(!isset($hasCapture))
         return false;

       return $this->getRequestBuilder()->setCardTowData($commandSubject);
    }
    
    protected function getResponseHandler()
    {
        return $this->responseHandler;
    }

    protected function setResponseHandler(ResponseHandler $responseHandler)
    {
        $this->responseHandler = $responseHandler;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestHandler()
    {
        return $this->requestHandler;
    }

    /**
     * @param mixed $requestHandler
     */
    public function setRequestHandler($requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    protected function getApi()
    {
        return $this->api;
    }

    protected function setApi(BraspagApi $api)
    {
        $this->api = $api;

        return $this;
    }

    protected function getRequestBuilder()
    {
        return $this->requestBuilder;
    }

    protected function setRequestBuilder(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;

        return $this;
    }

    protected function getRequestValidator()
    {
        return $this->requestValidator;
    }

    protected function setRequestValidator(ValidatorInterface $validator = null)
    {
        $this->requestValidator = $validator;

        return $this;
    }

    protected function getResponseValidator()
    {
        return $this->responseValidator;
    }

    protected function setResponseValidator(ValidatorInterface $validator = null)
    {
        $this->responseValidator = $validator;

        return $this;
    }
}
