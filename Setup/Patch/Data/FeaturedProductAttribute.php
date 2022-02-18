<?php

namespace Extensions\FeaturedProduct\Setup\Patch\Data;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

/**
 * Class FeaturedProductAttribute
 *
 * @package Extensions\FeaturedProduct\Setup\Patch\Data
 */
class FeaturedProductAttribute implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var string
     */
    const FEATURED_PRODUCT_ATTRIBUTE_CODE = 'featured_product';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $config
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        Config $config
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            self::FEATURED_PRODUCT_ATTRIBUTE_CODE,
            [
                'group' => 'General',
                'sort_order' => 41,
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Featured Product',
                'input' => 'boolean',
                'class' => '',
                'source' => Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'visible_in_advanced_search' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]
        );
        $featuredProductAttribute = $eavSetup->getAttribute(Product::ENTITY, self::FEATURED_PRODUCT_ATTRIBUTE_CODE);

        $attributeId = $featuredProductAttribute['attribute_id'] ?? null;
        $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        $attributeSetIds = $eavSetup->getAllAttributeSetIds();
        foreach ($attributeSetIds as $attributeSetId) {
            if ($attributeSetId) {
                $groupId = $this->config->getAttributeGroupId($attributeSetId, 'General');
                $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $groupId, $attributeId);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
