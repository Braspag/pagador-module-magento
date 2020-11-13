<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Webjump\BraspagPagador\Block\Payment\Info;

use Magento\Payment\Block\Info;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template\Context;
use Webjump\BraspagPagador\Model\Payment\Info\BoletoFactoryInterface;
use Webjump\BraspagPagador\Model\Payment\Info\BoletoFactory;

class Boleto extends Info
{
    const TEMPLATE = 'Webjump_BraspagPagador::payment/info/boleto.phtml';

    /** @var BoletoFactory */
    protected $boletoFactory;

    public function __construct(
        Context $context,
        BoletoFactoryInterface $boletoFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->boletoFactory = $boletoFactory;
    }

    public function _construct()
    {
        $this->setTemplate(self::TEMPLATE);
    }

    /**
     * @param \Magento\Framework\DataObject|array|null $transport
     * @return \Magento\Framework\DataObject
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        $boleto = $this->boletoFactory->create($this->getInfo()->getOrder());

        $transport = new DataObject([
            (string)__('Print Boleto') => $boleto->getBoletoUrl()
        ]);

        $transport = parent::_prepareSpecificInformation($transport);

        return $transport;
    }
}
