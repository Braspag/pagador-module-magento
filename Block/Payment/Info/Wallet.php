<?php

/**
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 *  @author Esmerio Neto <esmerio.neto@signativa.com.br>
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Block\Payment\Info;

use Magento\Payment\Block\Info;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template\Context;
use Braspag\BraspagPagador\Model\Payment\Info\WalletFactoryInterface;
use Braspag\BraspagPagador\Model\Payment\Info\WalletFactory;

class Wallet extends Info
{
    const TEMPLATE = 'Braspag_BraspagPagador::payment/info/wallet.phtml';

    /** @var WalletFactory */
    protected $walletFactory;

    public function __construct(
        Context $context,
        WalletFactoryInterface $walletFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->walletFactory = $walletFactory;
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
        $wallet = $this->walletFactory->create($this->getInfo()->getOrder());

        $transport = new DataObject([
            (string)__('Print Wallet') => $wallet->getWalletUrl()
        ]);

        $transport = parent::_prepareSpecificInformation($transport);

        return $transport;
    }
}