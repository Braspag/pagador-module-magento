<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Gateway\Transaction\Resource\Pix\Send\Response;

use Magento\Payment\Gateway\Validator\Result;
use Magento\Payment\Gateway\Validator\ValidatorInterface;

class PixValidator implements ValidatorInterface
{

    public function validate(array $validationSubject)
    {
        if (isset($validationSubject['error'])) { // there was an error in the transaction
            /**
             * @var Exception $errorData
             */
            $errorData = $validationSubject['error'];
            $request = $errorData->getResponse();
            return new Result(false, [__($request['message'])]);
        }

        return new Result(true, []);
    }
}