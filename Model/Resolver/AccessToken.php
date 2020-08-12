<?php
/**
 * @author      Webjump Developer Team <developer@webjump.com.br>
 * @copyright   2020 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br Copyright
 *
 * @link        http://www.webjump.com.br
 */

declare(strict_types=1);

namespace Webjump\BraspagPagador\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class AccessToken implements ResolverInterface
{
    private $accessTokenDataProvider;

    /**
     * @param DataProvider\AccessToken $accessTokenDataProvider
     */
    public function __construct(
        DataProvider\AccessToken $accessTokenDataProvider
    ) {
        $this->accessTokenDataProvider = $accessTokenDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        return $this->accessTokenDataProvider->getData($args);
    }
}
