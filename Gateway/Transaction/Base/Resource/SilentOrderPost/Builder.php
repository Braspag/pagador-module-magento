<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Resource\SilentOrderPost;

use Webjump\BraspagPagador\Gateway\Transaction\Base\Config\SilentOrderPostConfigInterface;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\ClientInterface;

class Builder implements BuilderInterface
{
	protected $config;

	protected $transferBuilder;

	protected $client;

	public function __construct(
		SilentOrderPostConfigInterface $config,
		TransferBuilder $transferBuilder,
		ClientInterface $client
	) {
		$this->setConfig($config);
		$this->setTransferBuilder($transferBuilder);
		$this->setClient($client);
	}

	public function build()
	{
        $transferO = $this->getTransferBuilder()
        	->setUri($this->getConfig()->getAccessTokenUrl())
        	->setMethod(\Zend\Http\Request::METHOD_POST)
            ->setClientConfig([
                'timeout' => 30
            ])->setHeaders(
                [
                  'cache-control' => 'no-cache',
                  'content-type' => 'application/json; charset=utf-8'
                ]
            )->build();

        $response = $this->client->placeRequest($transferO);

        return $response->getAccessToken();
	}

    protected function getClient()
    {
        return $this->client;
    }

    protected function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return SilentOrderPostConfigInterface
     */
    protected function getConfig()
    {
        return $this->config;
    }

    protected function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return TransferBuilder
     */
    protected function getTransferBuilder()
    {
        return $this->transferBuilder;
    }

    protected function setTransferBuilder($transferBuilder)
    {
        $this->transferBuilder = $transferBuilder;

        return $this;
    }
}
