<?php

namespace Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface;
use Webjump\BraspagPagador\Api\CardTokenRepositoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\DataObject;

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

    protected $eventManager;

    public function __construct(
        CardTokenRepositoryInterface $cardTokenRepository,
        ManagerInterface $eventManager
    ) {
        $this->setCardTokenRepository($cardTokenRepository);
        $this->setEventManager($eventManager);
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

        $data = new DataObject([
            'alias' => sprintf('xxxx-%s', $payment->getCcLast4()),
            'token' => $response->getPaymentCardToken(),
            'provider' => $response->getPaymentCardProvider(),
            'brand' => $response->getPaymentCardBrand(),
        ]);

        $this->getEventManager()->dispatch(
            'braspag_creditcard_token_handler_save_before',
            ['card_data' => $data, 'payment' => $payment, 'response' => $response]
        );

        $cardToken = $this->getCardTokenRepository()->create($data->toArray());

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

    protected function getEventManager()
    {
        return $this->eventManager;
    }

    protected function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;

        return $this;
    }
}
