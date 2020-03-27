<?php

namespace Webjump\BraspagPagador\Test\Unit\Gateway\Transaction\CreditCard\Resource\Authorize\Response;

use Magento\Sales\Model\Order;
use Webjump\BraspagPagador\Gateway\Transaction\CreditCard\Resource\Authorize\Response\CardTokenHandler;
use Magento\Framework\DataObject;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use \Magento\Framework\Api\SearchCriteria;
use Webjump\Braspag\Pagador\Transaction\Api\CreditCard\Send\ResponseInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Webjump\Braspag\Pagador\Transaction\Resource\CreditCard\Send\Response;

class CardTokenHandlerTest extends \PHPUnit\Framework\TestCase
{
	private $handler;

    private $eventManagerMock;

    private $cardTokenRepositoryMock;

    protected $objectManagerHelper;

    protected $orderMock;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilderMock;

    /**
     * @var SearchCriteria
     */
    private $searchCriteriaMock;

    /**
     * @var ResponseInterface
     */
    private $responseMock;

    /**
     * @var SearchResultsInterface
     */
    private $searchResultMock;

    public function setUp()
    {
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->cardTokenRepositoryMock = $this->createMock('Webjump\BraspagPagador\Api\CardTokenRepositoryInterface');

        $this->eventManagerMock = $this->createMock('Magento\Framework\Event\ManagerInterface');

        $this->orderMock = $this->getMockBuilder(Order::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addFilter', 'create'])
            ->getMock();

        $this->searchCriteriaMock = $this->getMockBuilder(SearchCriteria::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->responseMock = $this->createMock(Response::class);

        $this->searchResultMock = $this->getMockBuilder(SearchResultsInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems'])
            ->getMockForAbstractClass();

    	$this->handler = $this->objectManagerHelper->getObject(
            CardTokenHandler::class,
            [
                'cardTokenRepository' => $this->cardTokenRepositoryMock,
                'eventManager' => $this->eventManagerMock,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilderMock,
            ]
        );
    }

    public function tearDown()
    {

    }

    public function testHandle()
    {
        $data = new DataObject([
            'alias' => 'xxxx-5466',
            'token' => '6e1bf77a-b28b-4660-b14f-455e2a1c95e9',
            'provider' => 'Cielo',
            'brand' => 'Visa',
            'mask'  => '364135'
        ]);

        $this->cardTokenRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->with($data->getToken())
            ->will($this->returnValue(null));

        $this->searchCriteriaBuilderMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->searchCriteriaMock);

        $this->cardTokenRepositoryMock
            ->expects($this->once())
            ->method('getList')
            ->with($this->searchCriteriaMock)
            ->willReturn($this->searchResultMock);

        $this->searchResultMock
            ->expects($this->once())
            ->method('getItems')
            ->willReturn([]);

        $this->responseMock->expects($this->exactly(3))
            ->method('getPaymentCardToken')
            ->will($this->returnValue('6e1bf77a-b28b-4660-b14f-455e2a1c95e9'));

        $this->responseMock->expects($this->once())
            ->method('getPaymentCardProvider')
            ->will($this->returnValue('Cielo'));

        $this->responseMock->expects($this->exactly(2))
            ->method('getPaymentCardBrand')
            ->will($this->returnValue('Visa'));

        $this->responseMock->expects($this->once())
            ->method('getPaymentAuthorizationCode')
            ->will($this->returnValue('364135'));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentMock->expects($this->once())
            ->method('getCcLast4')
            ->will($this->returnValue('5466'));

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $paymentMock
            ->expects($this->atLeastOnce())
            ->method('getOrder')
            ->will($this->returnValue($this->orderMock));

        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($cardTokenMock));

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('save')
            ->with($cardTokenMock)
            ->will($this->returnValue($cardTokenMock));

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch');

    	$handlingSubject = ['payment' => $paymentDataObjectMock];
    	$response = ['response' => $this->responseMock];

    	$this->handler->handle($handlingSubject, $response);
    }

    public function testHandleWithAlreadyCardToken()
    {
        $this->responseMock->expects($this->exactly(2))
            ->method('getPaymentCardToken')
            ->will($this->returnValue('6e1bf77a-b28b-4660-b14f-455e2a1c95e9'));

        $paymentMock = $this->getMockBuilder('Magento\Sales\Model\Order\Payment')
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDataObjectMock = $this->getMockBuilder('Magento\Payment\Gateway\Data\PaymentDataObjectInterface')
            ->setMethods(['getOrder', 'getShippingAddress', 'getPayment'])
            ->getMock();

        $paymentDataObjectMock->expects($this->once())
            ->method('getPayment')
            ->will($this->returnValue($paymentMock));

        $cardTokenMock = $this->getMockBuilder('Webjump\BraspagPagador\Model\CardToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cardTokenRepositoryMock->expects($this->once())
            ->method('get')
            ->with('6e1bf77a-b28b-4660-b14f-455e2a1c95e9')
            ->will($this->returnValue($cardTokenMock));

        $handlingSubject = ['payment' => $paymentDataObjectMock];
        $response = ['response' => $this->responseMock];

        $this->handler->handle($handlingSubject, $response);
    }
}
