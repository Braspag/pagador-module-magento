<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Command;

use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\CommandInterface;
use Webjump\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
use Magento\Payment\Gateway\Request\BuilderInterface as RequestBuilder;
use Magento\Payment\Gateway\Response\HandlerInterface as ResponseHandler;
use Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\Request\HandlerInterface as RequestHandler;
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

    public function __construct(
        BraspagApi $api,
        RequestBuilder $requestBuilder,
        RequestHandler $requestHandler,
        ResponseHandler $responseHandler,
        ValidatorInterface $requestValidator = null,
        ValidatorInterface $responseValidator = null
    )
    {
        $this->setApi($api);
        $this->setRequestBuilder($requestBuilder);
        $this->setResponseHandler($responseHandler);
        $this->setRequestHandler($requestHandler);
        $this->setRequestValidator($requestValidator);
        $this->setResponseValidator($responseValidator);
    }

    public function execute(array $commandSubject)
    {
        $request = $this->getRequestBuilder()->build($commandSubject);

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

        $this->getRequestHandler()->handle($commandSubject, ['request' => $request]);

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

        $this->getResponseHandler()->handle($commandSubject, ['response' => $response]);

        return $this;
    }

    abstract protected function sendRequest($request);

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
