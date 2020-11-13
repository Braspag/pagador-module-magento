<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\AntiFraud\Resource\MDD;

use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Resource\MDD\GeneralRequest;
use PHPUnit\Framework\TestCase;
use Detection\MobileDetect;
use Webjump\BraspagPagador\Gateway\Transaction\AntiFraud\Config\MDDConfigInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use \Magento\Customer\Api\Data\CustomerInterface;
use \Magento\Quote\Model\Quote;
use Magento\Payment\Gateway\Data\AddressAdapterInterface;
use \Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Address;
use Magento\Payment\Model\InfoInterface;

class GeneralRequestTest extends TestCase
{
    /**
     * @var MobileDetect
     */
    private $mobileDetectMock;

    /**
     * @var MDDConfigInterface
     */
    private $mddConfigInterfacetMock;

    /**
     * @var OrderCollectionFactory
     */
    private $orderCollectionFactoryMock;

    /**
     * @var CustomerInterface
     */
    private $customerInterfaceMock;

    /**
     * @var Quote
     */
    private $quoteMock;

    /**
     * @var AddressAdapterInterface
     */
    private $addressAdapterInterfaceMock;

    /**
     * @var Item
     */
    private $itemMock;

    /**
     * @var Product
     */
    private $productMock;

    /**
     * @var InfoInterface
     */
    private $paymentDataMock;

    /**
     * @var InfoInterface
     */
    protected $helperData;

    protected function setUp()
    {
        $this->mobileDetectMock = $this->createMock(MobileDetect::class);
        $this->mddConfigInterfacetMock = $this->createMock(MDDConfigInterface::class);
        $this->orderCollectionFactoryMock = $this->createMock(OrderCollectionFactory::class);
        $this->customerInterfaceMock = $this->createMock(CustomerInterface::class);
        $this->quoteMock = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCouponCode','getCustomerIsGuest', 'getBillingAddress','getShippingAddress','getAllVisibleItems','getShippingMethod', 'getGiftMessageId', 'getGrandTotal'])
            ->getMock();

        $this->addressAdapterInterfaceMock = $this->getMockBuilder(AddressAdapterInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPostcode', 'getShippingAmount','getTelephone'])
            ->getMockForAbstractClass();

        $this->itemMock = $this->createMock(Item::class);
        $this->paymentDataMock = $this->createMock(InfoInterface::class);
        $this->productMock = $this->createMock(Product::class);

        $this->helperData = $this->getMockBuilder('\Webjump\BraspagPagador\Helper\Data')
            ->disableOriginalConstructor()
            ->setMethods(['removeSpecialCharacters'])
            ->getMock();
    }

    /**
     * @return GeneralRequest
     */
    private function getModel()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        /** @var Request $model */
        $model = $objectManager->getObject(GeneralRequest::class, [
            'config' => $this->mddConfigInterfacetMock,
            'orderCollectionFactory' => $this->orderCollectionFactoryMock,
            'mobileDetect' => $this->mobileDetectMock,
            'helperData' => $this->helperData
        ]);

        return $model;
    }

    public function testGetCustomerName()
    {
        $firstName = "John";
        $lastName="Doe";
        $expectedResult = trim($firstName." ".$lastName);


        $this->mddConfigInterfacetMock
            ->expects($this->exactly(2))
            ->method('getCustomer')
            ->willReturn($this->customerInterfaceMock);

        $this->customerInterfaceMock
            ->expects($this->once())
            ->method('getFirstName')
            ->willReturn($firstName);

        $this->customerInterfaceMock
            ->expects($this->once())
            ->method('getLastName')
            ->willReturn($lastName);

        $this->helperData->expects($this->once())
            ->method('removeSpecialCharacters')
            ->willReturn($expectedResult);

        $requestModel = $this->getModel();
        $result = $requestModel->getCustomerName();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCustomerIsLoggedReturnTrue()
    {
        $expectedResult = "Sim";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);


        $this->quoteMock
            ->expects($this->once())
            ->method('getCustomerIsGuest')
            ->willReturn(true);

        $requestModel = $this->getModel();
        $result = $requestModel->getCustomerIsLogged();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCustomerIsLoggedResultFalse()
    {
        $expectedResult = "Não";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);


        $this->quoteMock
            ->expects($this->once())
            ->method('getCustomerIsGuest')
            ->willReturn(false);

        $requestModel = $this->getModel();
        $result = $requestModel->getCustomerIsLogged();

        $this->assertSame($result, $expectedResult);
    }


    public function testGetPurchaseByThirdShouldReturnNo()
    {
        $expectedResult = "Não";

        $this->mddConfigInterfacetMock
            ->expects($this->exactly(2))
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getBillingAddress')
            ->willReturn($this->addressAdapterInterfaceMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->addressAdapterInterfaceMock);

        $this->addressAdapterInterfaceMock
            ->expects($this->exactly(2))
            ->method('getPostcode')
            ->will($this->onConsecutiveCalls("12379-777", "12379-777"));

        $requestModel = $this->getModel();
        $result = $requestModel->getPurchaseByThird();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetPurchaseByThirdShouldReturnYes()
    {
        $expectedResult = "Sim";

        $this->mddConfigInterfacetMock
            ->expects($this->exactly(2))
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getBillingAddress')
            ->willReturn($this->addressAdapterInterfaceMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->addressAdapterInterfaceMock);

        $this->addressAdapterInterfaceMock
            ->expects($this->exactly(2))
            ->method('getPostcode')
            ->will($this->onConsecutiveCalls("12379-777", "12379-999"));

        $requestModel = $this->getModel();
        $result = $requestModel->getPurchaseByThird();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetSalesOrderChannelShouldReturnMovel()
    {
        $expectedResult = "Movel";

        $this->mobileDetectMock
            ->expects($this->once())
            ->method('isMobile')
            ->willReturn(true);

        $requestModel = $this->getModel();
        $result = $requestModel->getSalesOrderChannel();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetSalesOrderChannelShouldReturnWeb()
    {
        $expectedResult = "Web";

        $this->mobileDetectMock
            ->expects($this->once())
            ->method('isMobile')
            ->willReturn(false);

        $this->mobileDetectMock
            ->expects($this->once())
            ->method('isTablet')
            ->willReturn(false);

        $requestModel = $this->getModel();
        $result = $requestModel->getSalesOrderChannel();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetProductCategory()
    {
        $expectedResult = "sku, name";
        $items = [$this->itemMock, $this->itemMock];

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getAllVisibleItems')
            ->willReturn($items);

        $this->itemMock
            ->expects($this->exactly(2))
            ->method('getProduct')
            ->willReturn($this->productMock);

        $attributeCodeMock = "attribute";
        $this->mddConfigInterfacetMock
            ->expects($this->exactly(2))
            ->method('getCategoryAttributeCode')
            ->willReturn($attributeCodeMock);

        $this->productMock
            ->expects($this->exactly(2))
            ->method('getData')
            ->with($attributeCodeMock)
            ->will($this->onConsecutiveCalls("sku", "name"));

        $requestModel = $this->getModel();
        $result = $requestModel->getProductCategory();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetShippingMethod()
    {
        $expectedResult = "Shipping Method Mock";
        $shippingAddress = $this->createMock(Address::class);
        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($shippingAddress);

        $shippingAddress
            ->expects($this->once())
            ->method('getShippingMethod')
            ->willReturn($expectedResult);

        $requestModel = $this->getModel();
        $result = $requestModel->getShippingMethod();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCouponCode()
    {
        $expectedResult = "COUPON1234";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getCouponCode')
            ->willReturn($expectedResult);

        $requestModel = $this->getModel();
        $result = $requestModel->getCouponCode();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCustomerFetchSelfShouldReturnNo()
    {
        $expectedResult = "Não";

        $shippingMethodMock1 = "Shipping 1";
        $shippingMethodMock2 = "Shipping 2";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getShippingMethod')
            ->willReturn($shippingMethodMock1);

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getFetchSelfShippingMethod')
            ->willReturn($shippingMethodMock2);

        $requestModel = $this->getModel();
        $result = $requestModel->getCustomerFetchSelf();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCustomerFetchSelfShouldReturnYes()
    {
        $expectedResult = "Sim";
        $shippingMethodMock1 = "Shipping 1";
        $shippingMethodMock2 = "Shipping 1";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getShippingMethod')
            ->willReturn($shippingMethodMock1);

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getFetchSelfShippingMethod')
            ->willReturn($shippingMethodMock2);

        $requestModel = $this->getModel();
        $result = $requestModel->getCustomerFetchSelf();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetStoreCode()
    {
        $expectedResult = "StoreCodeMock";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getStoreCode')
            ->willReturn($expectedResult);

        $requestModel = $this->getModel();
        $result = $requestModel->getStoreCode();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetHasGiftCardShouldReturnYes()
    {
        $expectedResult = "Sim";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getGiftMessageId')
            ->willReturn(true);

        $requestModel = $this->getModel();
        $result = $requestModel->getHasGiftCard();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetHasGiftCardShouldReturnNo()
    {
        $expectedResult = "Não";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getGiftMessageId')
            ->willReturn(false);

        $requestModel = $this->getModel();
        $result = $requestModel->getHasGiftCard();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetShippingMethodAmount()
    {
        $expectedResult = "12.50";
        $floatMock = "12.500";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($this->addressAdapterInterfaceMock);

        $this->addressAdapterInterfaceMock
            ->expects($this->once())
            ->method('getShippingAmount')
            ->willReturn($floatMock);

        $requestModel = $this->getModel();
        $result = $requestModel->getShippingMethodAmount();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetSalesOrderAmount()
    {
        $expectedResult = "129.50";
        $floatMock = "129.500";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getGrandTotal')
            ->willReturn($floatMock);

        $requestModel = $this->getModel();
        $result = $requestModel->getSalesOrderAmount();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCustomerIdentity()
    {
        $expectedResult = "38890070051";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getCustomer')
            ->willReturn($this->customerInterfaceMock);

        $this->customerInterfaceMock
            ->expects($this->once())
            ->method('getTaxvat')
            ->willReturn($expectedResult);

        $requestModel = $this->getModel();
        $result = $requestModel->getCustomerIdentity();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetStoreIdentity()
    {
        $expectedResult = "123";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getStoreIdentity')
            ->willReturn($expectedResult);


        $requestModel = $this->getModel();
        $result = $requestModel->getStoreIdentity();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetQtyInstallmentsOrder()
    {
        $expectedResult = 123;
        $parameterMock = 'cc_installments';
        $this->paymentDataMock
            ->expects($this->once())
            ->method('getAdditionalInformation')
            ->with($parameterMock)
            ->willReturn($expectedResult);


        $requestModel = $this->getModel();
        $requestModel->setPaymentData($this->paymentDataMock);
        $result = $requestModel->getQtyInstallmentsOrder();

        $this->assertSame($result, $expectedResult);
    }

    public function testGetCustomerTelephone()
    {
        $expectedResult = 1144556677;
        $phoneMock = "(11) 4455-6677";

        $this->mddConfigInterfacetMock
            ->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quoteMock);

        $this->quoteMock
            ->expects($this->once())
            ->method('getBillingAddress')
            ->willReturn($this->addressAdapterInterfaceMock);

        $this->addressAdapterInterfaceMock
            ->expects($this->once())
            ->method('getTelephone')
            ->willReturn($phoneMock);

        $requestModel = $this->getModel();
        $result = $requestModel->getCustomerTelephone();

        $this->assertSame($result, $expectedResult);
    }
}
