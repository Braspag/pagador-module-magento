<?php 


namespace Webjump\BraspagPagador\Model\Payment;
 
 
class Billet extends \Magento\Payment\Model\Method\AbstractMethod
{
	protected $_code = "braspag_pagador_billet";
	protected $_isOffline = true;
}