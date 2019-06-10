<?php

/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2017 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

namespace Webjump\BraspagPagador\Model;

use Webjump\Braspag\Pagador\Transaction\Resource\Auth3Ds20\Token\Response as Auth3Ds20TokenResponse;
use Webjump\BraspagPagador\Api\Auth3Ds20TokenManagerInterface;
use Webjump\Braspag\Pagador\Transaction\BraspagFacade;
use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\RequestInterface as Auth3Ds20TokenRequest;
use Webjump\Braspag\Pagador\Transaction\FacadeInterface as BraspagApi;
use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Command\TokenCommand;
use Webjump\BraspagPagador\Gateway\Transaction\Auth3Ds20\Resource\Token\BuilderInterface;
use Magento\Framework\DataObject;

class Auth3Ds20TokenManager implements Auth3Ds20TokenManagerInterface
{
    protected $request;
    protected $tokenCommand;
    protected $cookieManager;
    protected $cookieMetadataFactory;
    protected $builder;
    protected $dataObject;
    protected $tokenObject;

    /**
     * Auth3Ds20TokenManager constructor.
     * @param Auth3Ds20TokenRequest $request
     * @param BraspagApi $api
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     */
    public function __construct(
        Auth3Ds20TokenRequest $request,
        TokenCommand $tokenCommand,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        BuilderInterface $builder,
        DataObject $dataObject
    ){
        $this->setTokenCommand($tokenCommand);
        $this->setRequest($request);
        $this->setCookieManager($cookieManager);
        $this->setCookieMetadataFactory($cookieMetadataFactory);
        $this->setBuilder($builder);
        $this->setDataObject($dataObject);
    }

    /**
     * @return Auth3Ds20TokenRequest
     */
    protected function getRequest(): Auth3Ds20TokenRequest
    {
        return $this->request;
    }

    /**
     * @param Auth3Ds20TokenRequest $request
     */
    protected function setRequest(Auth3Ds20TokenRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return TokenCommand
     */
    protected function getTokenCommand(): TokenCommand
    {
        return $this->tokenCommand;
    }

    /**
     * @param TokenCommand $tokenCommand
     */
    protected function setTokenCommand(TokenCommand $tokenCommand)
    {
        $this->tokenCommand = $tokenCommand;
    }

    /**
     * @return \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected function getCookieManager(): \Magento\Framework\Stdlib\CookieManagerInterface
    {
        return $this->cookieManager;
    }

    /**
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     */
    protected function setCookieManager(\Magento\Framework\Stdlib\CookieManagerInterface $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    /**
     * @return \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected function getCookieMetadataFactory(): \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
    {
        return $this->cookieMetadataFactory;
    }

    /**
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     */
    protected function setCookieMetadataFactory(\Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory)
    {
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * @return BuilderInterface
     */
    protected function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    /**
     * @param BuilderInterface $builder
     */
    protected function setBuilder(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return DataObject
     */
    protected function getDataObject(): DataObject
    {
        return $this->dataObject;
    }

    /**
     * @param DataObject $dataObject
     */
    protected function setDataObject(DataObject $dataObject)
    {
        $this->dataObject = $dataObject;
    }

    /**
     * @return mixed
     */
    protected function getTokenObject()
    {
        return $this->tokenObject;
    }

    /**
     * @param mixed $tokenObject
     */
    protected function setTokenObject($tokenObject)
    {
        $this->tokenObject = $tokenObject;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function getToken()
    {
        if (!$this->validateSavedTokenLife()) {
            $response = $this->getTokenCommand()->execute($this->getRequest());
            $this->setTokenObject($this->getBuilder()->build($response));
            $this->registerToken();
        }

        return $this->getSavedToken();
    }

    /**
     * @param Auth3Ds20TokenResponse $authenticationResponse
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    protected function registerToken()
    {
        $cookieMetadata = $this->getCookieMetadataFactory()->createPublicCookieMetadata()
            ->setHttpOnly(true)
            ->setDuration($this->getTokenObject()->getExpiresIn())
            ->setPath('/');

        $this->getCookieManager()->setPublicCookie(
            Auth3Ds20TokenRequest::BPMPI_ACCESS_TOKEN_COOKIE_NAME,
            $this->getTokenObject()->getToken(),
            $cookieMetadata
        );

        return $this;
    }

    protected function validateSavedTokenLife()
    {
        return (bool) $this->getCookieManager()->getCookie(Auth3Ds20TokenRequest::BPMPI_ACCESS_TOKEN_COOKIE_NAME, false);
    }

    protected function getSavedToken()
    {
        if ($this->getTokenObject() instanceof DataObject) {
            return [['token' => $this->getTokenObject()->getToken()]];
        }

        return [['token' => 
            $this->getCookieManager()
                ->getCookie(Auth3Ds20TokenRequest::BPMPI_ACCESS_TOKEN_COOKIE_NAME, null)
        ]];
    }
}
