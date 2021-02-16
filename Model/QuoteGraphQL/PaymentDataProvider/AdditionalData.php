<?php
/**
 * Created by PhpStorm.
 * User: jonatasavila
 * Date: 2/16/21
 * Time: 12:16 PM
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\QuoteGraphQL\PaymentDataProvider;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\QuoteGraphQl\Model\Cart\Payment\AdditionalDataProviderInterface;

/**
 * Braspag Dataprovider
 */
class AdditionalData implements AdditionalDataProviderInterface
{
    private const PATH_ADDITIONAL_DATA = 'additional_data';

    /**
     * Array Manager
     *
     * @var \Magento\Framework\Stdlib\ArrayManager
     **/
    private $arrayManager;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     */
    public function __construct(
        \Magento\Framework\Stdlib\ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    /**
     * Return Additional Data
     *
     * @param array $data
     * @return array
     */
    public function getData($data): array
    {
        if (empty($data[self::PATH_ADDITIONAL_DATA])) {
            throw new GraphQlInputException(
                __('Required parameter "additional_data" for "payment_method" is missing.')
            );
        }

        return $this->arrayManager->get(self::PATH_ADDITIONAL_DATA, $data);
    }
}
