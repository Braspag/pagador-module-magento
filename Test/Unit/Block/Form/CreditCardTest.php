<?php
namespace Webjump\BraspagPagador\Test\Unit\Block\Form;

use Magento\Payment\Model\MethodInterface;
use Webjump\BraspagPagador\Block\Form\CreditCard;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Payment\Model\Config;
use Webjump\BraspagPagador\Api\InstallmentsInterface;

class CreditCardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CreditCard
     */
    private $model;

    /**
     * @var Config
     */
    private $configMock;

    /**
     * @var Context
     */
    private $contextMock;

    /**
     * @var InstallmentsInterface
     */
    private $installmensMock;

    private $formMock;

    private $methodInterfaceMock;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->contextMock = $this->createMock(Context::class);
        $this->configMock = $this->createMock(Config::class);
        $this->installmensMock = $this->createMock(InstallmentsInterface::class);
        $this->formMock = $this->createMock(\Magento\Payment\Block\Form::class);

        $this->methodInterfaceMock = $this->getMockForAbstractClass(
            MethodInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getInfoInstance', 'getCode', 'getConfigData']
        );

        $this->model = $objectManager->getObject(
            CreditCard::class,
            [
                'context' => $this->contextMock,
                'paymentConfig' => $this->configMock,
                'installments' => $this->installmensMock,
                'data' => []
            ]
        );

    }

    public function testGetAllInstallments()
    {
        $installmentsMock = [
            [
                "value" => "1",
                "label" => "1x de R$ 60,00 sem juros"
            ],
            [
                "value" => "2",
                "label" => "2x de R$ 30,00 sem juros"
            ],
            [
                "value" => "3",
                "label" => "3x de R$ 20,00 sem juros"
            ]
        ];

        $this->installmensMock
            ->expects($this->once())
            ->method('getInstallments')
            ->willReturn($installmentsMock);

        $result = $this->model->getAllInstallments();
        $this->assertEquals($installmentsMock, $result);
    }

    public function testIsInstallmentsActiveShouldReturnTrue()
    {
        $this->methodInterfaceMock->expects($this->once())
            ->method('getConfigData')
            ->with('installments_active')
            ->willReturn(null);

        $this->model->setData('method', $this->methodInterfaceMock);

        $result = $this->model->isInstallmentsActive();
        $this->assertEquals(true, $result);
    }

    public function testIsInstallmentsActiveShouldReturnFalseWhenConfigIsSettedFalse()
    {
        $this->methodInterfaceMock->expects($this->once())
            ->method('getConfigData')
            ->with('installments_active')
            ->willReturn(false);

        $this->model->setData('method', $this->methodInterfaceMock);

        $result = $this->model->isInstallmentsActive();
        $this->assertEquals(false, $result);
    }

    public function testIsInstallmentsActiveShouldReturnTrueWhenInvalidMethod()
    {
        $this->methodInterfaceMock->expects($this->once())
            ->method('getConfigData')
            ->with('installments_active')
            ->willReturn(false);

        $this->model->setData('method', $this->methodInterfaceMock);

        $result = $this->model->isInstallmentsActive();
        $this->assertEquals(false, $result);
    }
}
