<?php

namespace Braspag\BraspagPagador\Controller\Installments;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;
use Braspag\BraspagPagador\Gateway\Transaction\Base\Resource\Installments\Builder;


class Index extends Action {


    protected $installmentFactory;

    protected $builder;

    /**
     * @var JsonFactory
     */
    protected $resultPageFactory;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        Context $context,  
        Builder $builder,
        JsonFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->builder = $builder;
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function execute()
    {
        $result = $this->resultPageFactory->create();
        $resultData = [];
        $amount = $this->getRequest()->getParam('amount');
        $cardType = $this->getRequest()->getParam('card');


        if(!$amount)
          return;
    
       
        /** @var Installment $item */
        foreach ($this->builder->build($amount, $cardType) as $item) {
            $resultData[] = [
                'id' => $item->getId(),
                'label' => $item->getLabel()
            ];
        }

        $result->setData($resultData);
        return $result;
    }

}