<?php
namespace Webjump\BraspagPagador\Test\Unit\Block\Form;


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

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->contextMock = $this->createMock(Context::class);
        $this->configMock = $this->createMock(Config::class);
        $this->installmensMock = $this->createMock(InstallmentsInterface::class);

        $this->model = $objectManager->getObject(
            CreditCard::class,
            [
                'context' => $this->contextMock,
                'paymentConfig' => $this->configMock,
                'installments' => $this->installmensMock
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
}
