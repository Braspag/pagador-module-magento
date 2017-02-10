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
use Webjump\BraspagPagador\Model\Payment\Info\BilletFactoryInterface;
use Webjump\BraspagPagador\Model\Payment\Info\BilletFactory;

class Billet extends Info
{
    const TEMPLATE = 'Webjump_BraspagPagador::payment/info/billet.phtml';

    /** @var BilletFactory */
    protected $billetFactory;

    public function __construct(
        Context $context,
        BilletFactoryInterface $billetFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->billetFactory = $billetFactory;
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
        $billet = $this->billetFactory->create($this->getInfo()->getOrder());
        
        $transport = new DataObject([
            (string)__('Print Billet') => $billet->getBilletUrl()
        ]);

        $transport = parent::_prepareSpecificInformation($transport);

        return $transport;
    }
}
