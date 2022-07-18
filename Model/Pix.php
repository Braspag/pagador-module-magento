<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Braspag\BraspagPagador\Model;

/**
 * Class Checkmo
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 *
 * @api
 * @since 100.0.2
 */
class Pix extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_PIX_CODE = 'braspag_pagador_pix';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_PIX_CODE;

    /**
     * @var string
     */
    protected $_formBlockType = \Braspag\BraspagPagador\Block\Form\Pix::class;

    /**
     * @var string
     */
    protected $_infoBlockType = \Braspag\BraspagPagador\Block\Info\Pix::class;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * @return string
     */
    public function getPayableTo()
    {
        return $this->getConfigData('payable_to');
    }

    /**
     * @return string
     */
    public function getMailingAddress()
    {
        return $this->getConfigData('mailing_address');
    }
}