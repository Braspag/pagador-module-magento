<?php
namespace Webjump\BraspagPagador\Gateway\Transaction\Base\Config;


interface ContextInterface
{
    public function getConfig();

    public function getSession();

    public function getStoreManager();

    public function getDateTime();

    public function getCurrentDate();
}
