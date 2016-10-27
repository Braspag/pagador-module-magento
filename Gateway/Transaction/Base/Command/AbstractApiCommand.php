<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Command;

use Magento\Payment\Gateway\CommandInterface;
use Webjump\Braspag\Pagador\Transaction\Resource\Facade\FacadeInterface as BraspagApi;
use Magento\Payment\Gateway\Request\BuilderInterface as RequestBuilder;
use Magento\Payment\Gateway\Response\HandlerInterface as ResponseHandler;

/**
 * Braspag Transaction Abstract Api Command
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
abstract class AbstractApiCommand implements CommandInterface
{
	protected $api;

	protected $requestBuilder;

	protected $responseHandler;

	public function __construct(
		BraspagApi $api,
		RequestBuilder $requestBuilder,
		ResponseHandler $responseHandler
	)
	{
		$this->setApi($api);
		$this->setRequestBuilder($requestBuilder);
		$this->setResponseHandler($responseHandler);		
	}

	public function execute(array $commandSubject)
	{
        $request = $this->getRequestBuilder()->build($commandSubject);

        $response = $this->sendRequest($request);

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
}
