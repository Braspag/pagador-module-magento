<?php

/*
 * Copyright (C) 2021 Signativa/FGP Desenvolvimento de Software
 *
 * SPDX-License-Identifier: Apache-2.0
 */

namespace Braspag\BraspagPagador\Model\Context;

interface ContextInterface
{
    public function getConfig();

    public function getSession();

    public function getStoreManager();

    public function getDateTime();

    public function getCurrentDate();
}