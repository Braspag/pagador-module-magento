<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;
/**

 * Braspag Transaction CreditCard Authorize Response Handler
 *
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */
class CardTokenHandler extends AbstractHandler implements HandlerInterface
{
    protected $cardTokenRepository;

    public function __construct(
        CardTokenRepositoryInterface $cardTokenRepository
    ) {
        $this->setCardTokenRepository($cardTokenRepository);
    }

    protected function _handle($payment, $response)
    {
        if ($response->getPaymentCardToken()) {
            $this->saveCardToken($payment, $response);
        }

        return $this;
    }

    protected function saveCardToken($payment, $response)
    {
        if ($cardToken = $this->getCardTokenRepository()->get($response->getPaymentCardToken())) {
            return $cardToken;
        }

        $cardToken = $this->getCardTokenRepository()->create(
            $response->getPaymentCardNumberEncrypted(),
            $response->getPaymentCardToken(),
            $response->getPaymentCardProvider(),
            $response->getPaymentCardBrand()
        );

        $this->getCardTokenRepository()->save($cardToken);

        return $cardToken;
    }

    protected function getCardTokenRepository()
    {
        return $this->CardTokenRepository;
    }

    protected function setCardTokenRepository(CardTokenRepositoryInterface $cardTokenRepository)
    {
        $this->CardTokenRepository = $cardTokenRepository;

        return $this;
    }
}
