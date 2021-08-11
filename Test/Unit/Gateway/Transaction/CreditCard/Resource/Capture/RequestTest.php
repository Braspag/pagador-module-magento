<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Capture;

use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Capture\Request;
use Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface as RequestPaymentSplitLibInterface;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    private $request;
    private $configMock;
    private $paymentSplitRequestMock;
    private $orderAdapterMock;
    private $dataObjectMock;

    public function setUp()
    {
        $this->configMock = $this->getMockBuilder('\Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Config\ConfigInterface')
            ->setMethods([
                'getMerchantId',
                'getMerchantKey',
                'getMerchantName',
                'getEstablishmentCode',
                'getMCC',
                'getIsTestEnvironment',
                'isAuthorizeAndCapture',
                'getSoftDescriptor',
                'getIdentityAttributeCode',
                'hasAntiFraud',
                'hasAvs',
                'getReturnUrl',
                'isSaveCardActive',
                'isCreateInvoiceOnNotificationCaptured',
                'getCustomerStreetAttribute',
                'getCustomerNumberAttribute',
                'getCustomerComplementAttribute',
                'getCustomerDistrictAttribute',
                'getDecimalGrandTotal',
                'isAuth3Ds20Active',
                'isAuth3Ds20MCOnlyNotifyActive',
                'isAuth3Ds20AuthorizedOnError',
                'isAuth3Ds20AuthorizedOnFailure',
                'isAuth3Ds20AuthorizeOnUnenrolled',
                'isAuth3Ds20AuthorizeOnUnsupportedBrand',
                'getAuth3Ds20Mdd1',
                'getAuth3Ds20Mdd2',
                'getAuth3Ds20Mdd3',
                'getAuth3Ds20Mdd4',
                'getAuth3Ds20Mdd5',
                'isPaymentSplitActive',
                'getPaymentSplitType',
                'getPaymentSplitTransactionalPostSendRequestAutomatically',
                'getPaymentSplitTransactionalPostSendRequestAutomaticallyAfterXHours',
                'getPaymentSplitDefaultMrd',
                'getPaymentSplitDefaultFee',
                'isCardViewActive',
                'isTestEnvironment'
            ])
            ->getMock();

        $this->orderAdapterMock = $this->getMockBuilder('\Magento\Payment\Gateway\Data\OrderAdapterInterface')
            ->setMethods([
                'getCurrencyCode',
                'getOrderIncrementId',
                'getCustomerId',
                'getBillingAddress',
                'getShippingAddress',
                'getStoreId',
                'getId',
                'getGrandTotalAmount',
                'getItems',
                'getRemoteIp',
            ])
            ->getMock();

        $this->paymentSplitRequestMock = $this->getMockBuilder('\Webjump\Braspag\Pagador\Transaction\Api\PaymentSplit\RequestInterface')
            ->setMethods([
                'getSplits'
            ])
            ->getMock();

        $this->dataObjectMock = $this->getMockBuilder(\Magento\Framework\DataObject::class)
            ->setMethods(['getData', 'getSubordinates'])
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

    	$this->request = $objectManager->getObject(Request::class, [
    	    'config' => $this->configMock
        ]);
    }

    public function tearDown()
    {

    }

    public function testGetData()
    {
        $this->configMock->expects($this->once())
            ->method('getMerchantId')
            ->will($this->returnValue('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $this->configMock->expects($this->once())
            ->method('getMerchantKey')
            ->will($this->returnValue('0123456789012345678901234567890123456789'));

        $this->configMock->expects($this->once())
            ->method('getIsTestEnvironment')
            ->will($this->returnValue(false));

        $subordinate = [
            'subordinate_merchant_id' => '12345678',
            'amount' => '12345678',
            'fares' => [
                'mdr' => 1.5,
                'fee' => 1.6,
            ]
        ];

        $this->dataObjectMock->expects($this->once())
            ->method('getSubordinates')
            ->willReturn([
                    $subordinate
                ]
            );

        $this->paymentSplitRequestMock->expects($this->once())
            ->method('getSplits')
            ->willReturn($this->dataObjectMock);

        $this->request->setOrderAdapter($this->orderAdapterMock);

        static::assertEquals([], $this->request->getRequestDataBody());

        $this->request->setPaymentSplitRequest($this->paymentSplitRequestMock);

        static::assertEquals('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', $this->request->getMerchantId());
        static::assertEquals('0123456789012345678901234567890123456789', $this->request->getMerchantKey());
        static::assertEquals(['amount' => 0], $this->request->getAdditionalRequest());
        static::assertFalse($this->request->isTestEnvironment());
        static::assertEquals(['SplitPayments' => [[
            "SubordinateMerchantId" => $subordinate['subordinate_merchant_id'],
            "Amount" => $subordinate['amount'],
            "Fares" => [
                "Mdr" => $subordinate['fares']['mdr'],
                "Fee" => $subordinate['fares']['fee']
            ]
        ]
        ]], $this->request->getRequestDataBody());


    }
}
