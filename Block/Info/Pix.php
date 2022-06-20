<?php

/**
 * Braspag Block Info Pix
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */

namespace Braspag\BraspagPagador\Block\Info;

class Pix extends \Magento\Payment\Block\Info
{
    /**
     * @var string
     */
    protected $_payableTo;

    /**
     * @var string
     */
    protected $_mailingAddress;

    /**
     * @var string
     */
    protected $_template = 'Braspag_BraspagPagador::info/pix.phtml';

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getPayableTo()
    {
        if ($this->_payableTo === null) {
            $this->_convertAdditionalData();
        }
        return $this->_payableTo;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getMailingAddress()
    {
        if ($this->_mailingAddress === null) {
            $this->_convertAdditionalData();
        }
        return $this->_mailingAddress;
    }

    /**
     * @deprecated 100.1.1
     * @return $this
     */
    protected function _convertAdditionalData()
    {
        $this->_payableTo = $this->getInfo()->getAdditionalInformation('payable_to');
        $this->_mailingAddress = $this->getInfo()->getAdditionalInformation('mailing_address');
        return $this;
    }

    /**
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('Braspag_BraspagPagador::info/pdf/pix.phtml');
        return $this->toHtml();
    }
}
