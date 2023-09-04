<?php

namespace Braspag\BraspagPagador\Block\Payment\Info;

use Magento\Payment\Block\Info;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template\Context;
use Braspag\BraspagPagador\Model\Payment\Info\CreditCardFactoryInterface;
use Braspag\BraspagPagador\Model\Payment\Info\CreditCardFactory;
use Magento\Framework\Pricing\Helper\Data;

class CreditCard extends Info
{
    const TEMPLATE = 'Braspag_BraspagPagador::payment/info/credit_card.phtml';

    /** @var CreditCardFactory */
    protected $creditCardFactory;

    protected $paymentInfo;

    protected $priceHelper;

    public function __construct(
        Context $context,
        CreditCardFactoryInterface $creditCardFactory,
        Data $priceHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->creditCardFactory = $creditCardFactory;
        $this->priceHelper = $priceHelper;
    }

    public function _construct()
    {
        $this->setTemplate(self::TEMPLATE);
    }

    /**
     * @param \Magento\Framework\DataObject|array|null $transport
     * @return \Magento\Framework\DataObject
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        $creditCard = $this->creditCardFactory->create($this->getInfo()->getOrder());

        $this->paymentInfo = $creditCard->getPayment();

        $data = new DataObject([
            'brand' => $this->paymentInfo->getCcType(),
            'last_4_digits' => $this->paymentInfo->getCcLast4()
        ]);

        return parent::_prepareSpecificInformation($data);
    }

    public function getCardInfo()
    {
        $cardLast4 =  ' - xxxx-xxxx-xxxx-' .$this->paymentInfo->getCcLast4();

        if ($this->paymentInfo->getAdditionalInformation('cc_token'))
          $cardLast4 =  ' - ' .$this->paymentInfo->getAdditionalInformation('cc_alias');

        return  $this->paymentInfo->getCcType().' '.  $cardLast4;

    }

    public function getCardInfoTwoCard()
    {
        $cardLast4 =  ' - xxxx-xxxx-xxxx-' .$this->paymentInfo->getAdditionalInformation('two_card_last_4');

        if ($this->paymentInfo->getAdditionalInformation('card_cc_token_card2'))
          $cardLast4 =  ' - ' .$this->paymentInfo->getAdditionalInformation('card_cc_alias_card2');

        return  $this->paymentInfo->getAdditionalInformation('two_card_type').' '.  $cardLast4;

    }

    public function getInstallmentsInfo()
    {

        $installments = $this->paymentInfo->getAdditionalInformation('cc_installments');
        $amountAuthorized = $this->paymentInfo->getAmountAuthorized();

        $twoCardAmount = $this->getTwoCardAmount();

        if($twoCardAmount)
        $amountAuthorized = $amountAuthorized - $this->getTwoCardAmount();

        $priceFormatted = $this->getPriceHelper()->currency(abs($amountAuthorized), true, false);
        $time = $installments > 1 ? 'times' : 'time';
        
        return $priceFormatted . ' em '. str_replace('*', '', $this->paymentInfo->getAdditionalInformation('cc_installments_text'));

    }

    public function getInstallmentsInfoTwoCard()
    {
        $installments = $this->paymentInfo->getAdditionalInformation('two_card_cc_installments');
        $amountAuthorized = $this->getTwoCardAmount();
        $priceFormatted = $this->getPriceHelper()->currency($amountAuthorized, true, false);
        $time = $installments > 1 ? 'times' : 'time';

        return $priceFormatted . ' em '. str_replace('*', '', $this->paymentInfo->getAdditionalInformation('cc_installments_text_card2'));

    }

    public function hasTwoCard()
    {
        $twoCardInfo = $this->paymentInfo->getAdditionalInformation('two_card_paymentId');
        return  isset($twoCardInfo);
    }

    public function getTwoCardCcOwner()
    {
        return  $this->paymentInfo->getAdditionalInformation('two_card_cc_owner');
    }


    public function getCardCcOwner()
    {
        return  $this->paymentInfo->getAdditionalInformation('cc_owner');
    }
  
    public function getTwoCardData($attr)
    {
        return  $this->paymentInfo->getAdditionalInformation($attr);
    }

    protected function getTwoCardAmount()
    {
        $amount = $this->paymentInfo->getAdditionalInformation('two_card_total_amount');

        if(!isset($amount))
         return false;

        return str_replace( ',', '.', str_replace('.' , '', $this->paymentInfo->getAdditionalInformation('two_card_total_amount')));

    }

    protected function getPriceHelper()
    {
        return $this->priceHelper;
    }

    protected function setPriceHelper(Data $priceHelper)
    {
        $this->priceHelper = $priceHelper;
        return $this;
    }

    
}