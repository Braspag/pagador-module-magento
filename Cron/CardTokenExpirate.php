<?php

declare(strict_types=1);

namespace Braspag\BraspagPagador\Cron;

use Braspag\BraspagPagador\Model\Config\ConfigInterface;
use Braspag\BraspagPagador\Api\CardTokenRepositoryInterface;
use Braspag\BraspagPagador\Model\CardTokenFactory;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;

/**
 * Class CardTokenExpirate
 */

class CardTokenExpirate
{

    /**
     * @var
     */
    protected $config;

    /**
     * @var
     */
    protected $cardTokenFactory;

    /**
     * @var
     */
    protected $encryptor;

    /**
     * Constructor
     *
     */
    public function __construct(
        ConfigInterface $config,
        CardTokenFactory $cardTokenFactory,
        Encryptor $encryptor
    ) {
        $this->config                = $config;
        $this->cardTokenFactory      = $cardTokenFactory;
        $this->encryptor             = $encryptor;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {

	    $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/braspag_cron_token.log');
$logger = new \Zend_Log();
$logger->addWriter($writer);
$logger->info('init');
	    
	 $collection = $this->cardTokenFactory->create()->getCollection();

        if(empty($collection))
         return ;

        foreach ($collection as $cadToken) {

            $dateToken = $cadToken->getData('date_expiration_token');
            if (!isset($dateToken) ) {
                $cadToken->delete();
                continue;
            }

            $dateExpiration = $this->encryptor->decrypt($dateToken);

            if ($dateExpiration <=  date('m/yy')) {
                $cadToken->delete();
            }

        }
    
    }

}
