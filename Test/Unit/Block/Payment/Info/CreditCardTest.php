<?php
namespace Webjump\BraspagPagador\Test\Unit\Block\Payment\Info;

use Faker\Provider\ar_SA\Payment;
use Magento\Framework\View\Element\Template\Context;
use Webjump\BraspagPagador\Block\Payment\Info\CreditCard;
use Webjump\BraspagPagador\Model\Payment\Info\CreditCardFactoryInterface;
use Magento\Payment\Block\Info;

class CreditCardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CreditCard
     */
    private $block;

    /**
     * @var Context
     */
    private $contextMock;

    /**
     * @var InstallmentsInterface
     */
    private $creditCardFactory;

    private $priceHelper;

    private $paymentInfoMock;
    private $paymentData;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->contextMock = $this->createMock(Context::class);

        $this->priceHelper = $this->createMock(\Magento\Framework\Pricing\Helper\Data::class);

        $this->paymentData = $this->createMock(\Magento\Sales\Api\Data\OrderPaymentInterface::class);

        $this->paymentInfoMock = $this->getMockBuilder('Magento\Payment\Block\Info')
            ->disableOriginalConstructor()
            ->setMethods(['getAdditionalInformation', 'getAmountAuthorized'])
            ->getMock();

        $this->creditCardFactory = $this->getMockBuilder(CreditCardFactoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->block = $objectManager->getObject(
            CreditCard::class,
            [
                'context' => $this->contextMock,
                'creditCardFactory' => $this->creditCardFactory,
                'priceHelper' => $this->priceHelper,
                'data' => []
            ]
        );

        $objectManager->setBackwardCompatibleProperty($this->block, 'paymentInfo', $this->paymentData);

    }

    public function testConstructShouldSetTemplateWithSuccess()
    {
        $result = $this->block->_construct();
    }

    public function testGetInstallmentsInfoShouldReturnInstallmentsInfoWithSuccess()
    {
        $result = $this->block->getInstallmentsInfo();

        $expected = __('%1 Splitted in %2 '. 'time', null, null);

        $this->assertEquals($expected, $result);
    }
}
