<?php
/**
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Auth3DS20;

use Webjump\BraspagPagador\Api\Auth3DS20GetAddressInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface;
use Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterfaceFactory;

/**
 * Class Auth3DS20GetAddress
 *
 * @package Webjump\BraspagPagador\Model
 */
class GetAddress implements Auth3DS20GetAddressInterface
{
    private $addressInformationInterfaceFactory;

    /**
     * Auth3DS20GetAddress constructor.
     *
     * @param Auth3DS20AddressInformationInterfaceFactory $addressInformationInterfaceFactory
     */
    public function __construct(Auth3DS20AddressInformationInterfaceFactory $addressInformationInterfaceFactory)
    {
        $this->addressInformationInterfaceFactory = $addressInformationInterfaceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getAddressData(\Magento\Quote\Api\Data\CartInterface $quote): \Webjump\BraspagPagador\Api\Data\Auth3DS20AddressInformationInterface
    {
        /** @var $auth3DS20AdressInformation Auth3DS20AddressInformationInterface */
        $auth3DS20AdressInformation = $this->addressInformationInterfaceFactory->create();
        $auth3DS20AdressInformation->setBpmpiBilltoCity($quote->getBillingAddress()->getCity());
        $auth3DS20AdressInformation->setBpmpiBilltoEmail($quote->getBillingAddress()->getEmail());
        $auth3DS20AdressInformation->setBpmpiBilltoCountry($quote->getBillingAddress()->getCountryId());
        $auth3DS20AdressInformation->setBpmpiBilltoState($quote->getBillingAddress()->getRegionCode());
        $auth3DS20AdressInformation->setBpmpiBilltoCustomerid((string)$quote->getBillingAddress()->getCustomerId());
        $auth3DS20AdressInformation->setBpmpiBilltoStreet1(implode(',', $quote->getBillingAddress()->getStreet()));
        $auth3DS20AdressInformation->setBpmpiBilltoStreet2(implode(',', $quote->getBillingAddress()->getStreet()));
        $auth3DS20AdressInformation->setBpmpiBilltoZipcode(preg_replace('/[^0-9]/','', $quote->getBillingAddress()->getPostcode()));
        $auth3DS20AdressInformation->setBpmpiBilltoPhoneNumber(preg_replace('/[^0-9]/','', $quote->getBillingAddress()->getTelephone()));
        $auth3DS20AdressInformation->setBpmpiShiptoAddressee($quote->getBillingAddress()->getFirstname());

        $auth3DS20AdressInformation->setBpmpiShiptoAddressee($quote->getShippingAddress()->getFirstname());
        $auth3DS20AdressInformation->setBpmpiShiptoPhonenumber(preg_replace('/[^0-9]/','', $quote->getShippingAddress()->getTelephone()));
        $auth3DS20AdressInformation->setBpmpiShiptoEmail($quote->getShippingAddress()->getEmail());
        $auth3DS20AdressInformation->setBpmpiShiptoStreet1(implode(',', $quote->getShippingAddress()->getStreet()));
        $auth3DS20AdressInformation->setBpmpiShiptoStreet2(implode(',', $quote->getShippingAddress()->getStreet()));
        $auth3DS20AdressInformation->setBpmpiShiptoPhonenumber(preg_replace('/[^0-9]/','', $quote->getShippingAddress()->getTelephone()));
        $auth3DS20AdressInformation->setBpmpiShiptoZipcode(preg_replace('/[^0-9]/','', $quote->getShippingAddress()->getPostCode()));
        $auth3DS20AdressInformation->setBpmpiShiptoCity($quote->getShippingAddress()->getCity());
        $auth3DS20AdressInformation->setBpmpiShiptoCountry($quote->getShippingAddress()->getCountryId());
        $auth3DS20AdressInformation->setBpmpiShiptoState($quote->getShippingAddress()->getRegionCode());

        return $auth3DS20AdressInformation;
    }
}
