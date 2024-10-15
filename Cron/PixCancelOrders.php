<?php

declare(strict_types=1);

namespace Braspag\BraspagPagador\Cron;

use Braspag\BraspagPagador\Model\Config\ConfigInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

/**
 * Class PixCancelOrders
 */

class PixCancelOrders
{

    protected $config;
    protected $orderCollectionFactory;
    protected $orderFactory;
    protected $logger;

    /**
     * Constructor
     *
     * @param \Braspag\BraspagPagador\Model\Config\ConfigInterface $config
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     */
    public function __construct(
       ConfigInterface $config,
       LoggerInterface $logger,
       CollectionFactory $orderCollectionFactory,
       OrderFactory $orderFactory 
    )
    {
        $this->config                 = $config;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderFactory           = $orderFactory;
        $this->logger                 = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {

	    $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/braspag_cron_pix.log');
$logger = new \Zend_Log();
$logger->addWriter($writer);
$logger->info('init');

        if(!$this->config->getPixCronCancelPending())
          return;
        
      
        $to = strtotime("-".$this->config->getDeadline()." days");     
        $orders = $this->getOrderCollection("braspag_pagador_pix", "new", $to);
    
        if (!empty($orders)) {
            foreach( $orders as $order) {
                $this->cancelOrder($order->getId());
            }
        }  

        $this->logger->info("Cronjob cancelOrders is executed.");
    
    }


     /**
     * cancelOrder
     *
     * @return void
     */
    protected function cancelOrder ($orderId) {

        try {

            $order = $this->orderFactory->create()->load($orderId);
            $payment = $order->getPayment();            
            $order->addStatusHistoryComment('Order Cancel Automatically');
            $order->cancel()->save();
           // $this->logger->info("Cronjob cancelOrders is executed." .$order->getIncrementId());

        } catch (\Exception $e) {
            $this->logger->error('Cancel Order Pix Error'. $e->getMessage());
        }
    }

    /**
     * getOrderCollection
     *
     * @return array
     */
    protected function getOrderCollection($method, $status, $to)
    {
        $start = date('Y-m-d' . ' 00:00:00', $to);
        $end = date('Y-m-d' . ' 23:59:59', $to); 
      
        $orderCollection = $this->orderCollectionFactory
            ->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('state', ['eq'=> $status])
            ->addFieldToFilter('created_at', array('to' => $end));
            
            $orderCollection->getSelect()
            ->join(
                ["sop" => "sales_order_payment"],
                'main_table.entity_id = sop.parent_id',
                array('method')
            )
            ->where('sop.method = ?',$method );  
        

        return $orderCollection;
    }
}

