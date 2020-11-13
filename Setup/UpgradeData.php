<?php
/**
 * Unirgy LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unirgy.com/LICENSE-M1.txt
 *
 * @category   Unirgy
 * @package    \Unirgy\Dropship
 * @copyright  Copyright (c) 2015-2016 Unirgy LLC (http://www.unirgy.com)
 * @license    http:///www.unirgy.com/LICENSE-M1.txt
 */

namespace Webjump\BraspagPagador\Setup;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    const MEDIUMTEXT_SIZE=16777216;
    const TEXT_SIZE=65536;
    protected $categorySetupFactory;

    public function __construct(
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '3.14.0') < 0) {
            $this->upgradeThreeFourteenZero($setup, $context);
        }

        $setup->endSetup();
    }

    protected function upgradeThreeFourteenZero($setup, $context)
    {
        /** @var \Magento\Catalog\Setup\CategorySetup $catalogSetup */
        $catalogSetup = $this->categorySetupFactory->create(['setup' => $setup]);

        $catalogSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'braspag_subordinate_merchantid',
            [
                'type' => 'text',
                'input' => 'text',
                'label' => 'Braspag Subordinate Merchant ID',
                'group' => 'General',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => 1,
                'required' => 0,
                'visible' => 1,
                'backend' => '',
                'visible_on_front' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'used_in_product_listing'=>true,
                'is_used_for_price_rules'=>true,
            ]
        );

        $catalogSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'braspag_subordinate_mdr',
            [
                'type' => 'text',
                'input' => 'text',
                'label' => 'Braspag Subordinate MDR',
                'group' => 'General',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => 1,
                'required' => 0,
                'visible' => 1,
                'backend' => '',
                'visible_on_front' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'used_in_product_listing'=>true,
                'is_used_for_price_rules'=>true,
            ]
        );

        $catalogSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'braspag_subordinate_fee',
            [
                'type' => 'text',
                'input' => 'text',
                'label' => 'Braspag Subordinate Fee',
                'group' => 'General',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => 1,
                'required' => 0,
                'visible' => 1,
                'backend' => '',
                'visible_on_front' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'is_filterable_in_grid' => true,
                'used_in_product_listing'=>true,
                'is_used_for_price_rules'=>true,
            ]
        );
    }
}
