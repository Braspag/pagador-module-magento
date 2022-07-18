<?php

/**
 * Braspag Block Payment Info Pix
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */

namespace Braspag\BraspagPagador\Block\Payment\Info;

use Magento\Payment\Block\Info;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template\Context;
use Braspag\BraspagPagador\Model\Payment\Info\PixFactoryInterface;
use Braspag\BraspagPagador\Model\Payment\Info\PixFactory;

class Pix extends Info
{
    const TEMPLATE = 'Braspag_BraspagPagador::payment/info/pix.phtml';

    protected $pixHelper;

    /** @var PixFactory */
    protected $pixFactory;

    protected $ccontext;

    public function __construct(
        \Braspag\BraspagPagador\Helper\Pix $pixHelper,
        PixFactoryInterface $pixFactory,
        Context $context,
        array $data = []
    ) {
        $this->pixHelper = $pixHelper;
        $this->pixFactory = $pixFactory;
        $this->ccontext = $context;
        parent::__construct($context, $data);
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
        $paymentInfo = $this->getInfo()->getOrder()->getPayment();
        // $paymentInfo = $this->getOrder()->getPayment();

        $transport = new DataObject([
            'Transaction ID' => $paymentInfo->getAdditionalInformation('pix_transaction_id'),
            'Expiration Date' => $this->pixHelper->prepareExpirationDate($this->getInfo()->getOrder(), "d/m/Y H:i"),
            'QRcode Image' =>  $paymentInfo->getAdditionalInformation('QrcodeBase64Image'),
            'QRcode' => $paymentInfo->getAdditionalInformation('QrCodeString')
        ]);

        return parent::_prepareSpecificInformation($transport);
    }
}