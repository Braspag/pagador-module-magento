<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Source;

class WalletTypes extends \Magento\Payment\Model\Source\Cctype
{
    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return [
            "Simulado",
            "Apple-Pay",
            "Samsung-Pay",
            "Google-Pay",
            "Visa-Checkout",
            "Masterpass",
            "PayPal"
        ];
    }
}
