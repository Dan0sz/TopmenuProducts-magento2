<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev
 * @package  : Dan0sz/TopmenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopmenuProducts\Setup;

use Dan0sz\TopmenuProducts\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

// @codingStandardsIgnoreFile
class UpgradeData implements UpgradeDataInterface
{
    const ATTR_GROUP_TOP_MENU_PRODUCTS_LABEL = 'Top Menu Products';

    /** @var EavSetupFactory $eavSetup */
    private $eavSetup;

    /** @var array $attributes */
    private $attributes = [
        Config::ATTR_TOPMENU_PRODUCT_ENABLED    => [
            'type'       => 'int',
            'label'      => 'Add product to Top Menu?',
            'input'      => 'boolean',
            'source'     => '\Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'default'    => false,
            'sort_order' => 0
        ],
        Config::ATTR_TOPMENU_PRODUCT_LABEL      => [
            'type'       => 'varchar',
            'label'      => 'Custom Label',
            'input'      => 'text',
            'source'     => '',
            'default'    => '',
            'sort_order' => 10
        ],
        Config::ATTR_TOPMENU_PRODUCT_SORT_ORDER => [
            'type'       => 'int',
            'label'      => 'Sort Order',
            'input'      => 'text',
            'source'     => '',
            'default'    => false,
            'sort_order' => 20
        ],
        Config::ATTR_TOPMENU_PRODUCT_IS_HOME    => [
            'type'       => 'int',
            'label'      => 'Link to Homepage',
            'input'      => 'boolean',
            'source'     => '\Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'default'    => false,
            'sort_order' => 30
        ]
    ];

    /**
     * UpgradeData constructor.
     *
     * @param EavSetupFactory $eavSetup
     */
    public function __construct(
        EavSetupFactory $eavSetup
    ) {
        $this->eavSetup = $eavSetup;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetup->create(['setup' => $setup]);

        $this->createAttributes($eavSetup);

        $this->addToAttributeSets($eavSetup);

        $setup->endSetup();
    }

    /**
     * @param EavSetup $setup
     *
     * @return EavSetup
     */
    private function createAttributes(EavSetup $setup)
    {
        foreach ($this->attributes as $attribute => $data) {
            $setup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $attribute,
                [
                    'group'                   => self::ATTR_GROUP_TOP_MENU_PRODUCTS_LABEL,
                    'type'                    => $data['type'],
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => $data['label'],
                    'input'                   => $data['input'],
                    'class'                   => '',
                    'source'                  => $data['source'],
                    'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => true,
                    'default'                 => $data['default'],
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false,
                    'apply_to'                => ''
                ]
            );
        }

        return $setup;
    }

    /**
     * @param EavSetup $setup
     *
     * @return EavSetup
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function addToAttributeSets(EavSetup $setup)
    {
        $entityTypeId    = $setup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetIds = $setup->getAllAttributeSetIds($entityTypeId);

        foreach ($attributeSetIds as $attributeSetId) {
            $setup->addAttributeGroup($entityTypeId, $attributeSetId, self::ATTR_GROUP_TOP_MENU_PRODUCTS_LABEL, 55);

            foreach ($this->attributes as $attribute => $data) {
                $setup->addAttributeToGroup($entityTypeId, $attributeSetId, self::ATTR_GROUP_TOP_MENU_PRODUCTS_LABEL, $attribute, $data['sort_order']);
            }
        }

        return $setup;
    }
}
