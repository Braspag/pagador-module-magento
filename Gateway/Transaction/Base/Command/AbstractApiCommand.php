<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Command;

use Magento\Payment\Gateway\CommandInterface;
use Webjump\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
use Magento\Payment\Gateway\Request\BuilderInterface as RequestBuilder;
use Magento\Payment\Gateway\Response\HandlerInterface as ResponseHandler;
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

    protected $validator;

	public function __construct(
		BraspagApi $api,
		RequestBuilder $requestBuilder,
		ResponseHandler $responseHandler,
        ValidatorInterface $validator = null
	)
	{
		$this->setApi($api);
		$this->setRequestBuilder($requestBuilder);
		$this->setResponseHandler($responseHandler);
        $this->setValidator($validator);
	}

	public function execute(array $commandSubject)
	{
        $request = $this->getRequestBuilder()->build($commandSubject);

        $response = $this->sendRequest($request);
        if ($this->getValidator()) {
            $result = $this->getValidator()->validate(
                array_merge($commandSubject, ['response' => $response])
            );
            if (!$result->isValid()) {
                $errorMessage = $result->getFailsDescription();
                throw new CommandException(
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

    protected function getValidator()
    {
        return $this->validator;
    }

    protected function setValidator(ValidatorInterface $validator = null)
    {
        $this->validator = $validator;

        return $this;
    }
}
