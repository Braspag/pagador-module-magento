<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Webjump\BraspagPagador\Api\Auth3DS20UserAccountInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterfaceFactory;

/**
 * Class Auth3DS20UserAccount
 *
 * @package Webjump\BraspagPagador\Model
 */
class UserAccount implements Auth3DS20UserAccountInterface
{
    /**
     * @var Auth3DS20UserAccountInformationInterfaceFactory
     */
    private $userAccountInformationFactory;

    /**
     * Auth3DS20UserAccount constructor.
     * @param Auth3DS20UserAccountInformationInterfaceFactory $userAccountInformationFactory
     */
    public function __construct(
        Auth3DS20UserAccountInformationInterfaceFactory $userAccountInformationFactory
    ){
        $this->userAccountInformationFactory = $userAccountInformationFactory;
    }

    /**
     * @inheritDoc
     */
    public function getUserAccount(\Magento\Quote\Api\Data\CartInterface $quote): \Webjump\BraspagPagador\Api\Data\Auth3DS20UserAccountInformationInterface
    {
        /** @var $auth3DS20UserAccount Auth3DS20UserAccountInformationInterface */
        $auth3DS20UserAccount = $this->userAccountInformationFactory->create();

        $auth3DS20UserAccount->setBpmpiUseraccountCreateddate($quote->getCreatedAt());
//       $auth3DS20UserAccount->setBpmpiUseraccountGuest($quote->getCustomerIsGuest());
        $auth3DS20UserAccount->setBpmpiUseraccountAuthenticationmethod(02);
        $auth3DS20UserAccount->setBpmpiUseraccountChangeddate($quote->getUpdatedAt());
        $auth3DS20UserAccount->setBpmpiDeviceIpaddress($quote->getRemoteIp());

        return $auth3DS20UserAccount;
    }
}
