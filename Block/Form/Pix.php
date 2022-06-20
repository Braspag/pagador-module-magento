<?php

/**
 * Braspag Block Form Pix
 *
 * @author      Esmerio Neto <esmerio.neto@signativa.com.br>
 * @copyright   (C) 2021 Signativa/FGP Desenvolvimento de Software
 * SPDX-License-Identifier: Apache-2.0
 *
 */

namespace Braspag\BraspagPagador\Block\Form;

class Pix extends \Magento\Payment\Block\Form
{
    /**
     * Pix template
     *
     * @var string
     */
    protected $_template = 'Braspag_BraspagPagador::form/pix.phtml';
}