<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev
 * @package  : Dan0sz/TopMenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopMenuProducts\Setup;

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
        'top_menu_product_enabled'    => [
            'type'       => 'int',
            'label'      => 'Add product to Top Menu?',
            'input'      => 'boolean',
            'source'     => '\Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'sort_order' => 0
        ],
        'top_menu_product_label'      => [
            'type'       => 'varchar',
            'label'      => 'Custom Label',
            'input'      => 'text',
            'source'     => '',
            'sort_order' => 10
        ],
        'top_menu_product_sort_order' => [
            'type'       => 'int',
            'label'      => 'Sort Order',
            'input'      => 'text',
            'source'     => '',
            'sort_order' => 20
        ],
        'top_menu_product_is_home'    => [
            'type'       => 'int',
            'label'      => 'Link to Homepage',
            'input'      => 'boolean',
            'source'     => '\Magento\Eav\Model\Entity\Attribute\Source\Boolean',
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

        $this->addAttributes($eavSetup);

        $this->addAttributeGroup($eavSetup);

        $setup->endSetup();
    }

    /**
     * @param EavSetup $setup
     *
     * @return EavSetup
     */
    private function addAttributes(EavSetup $setup)
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
                    'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => true,
                    'default'                 => false,
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
    private function addAttributeGroup(EavSetup $setup)
    {
        $entityTypeId    = $setup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetIds = $setup->getAllAttributeSetIds($entityTypeId);

        foreach ($attributeSetIds as $attributeSetId) {
            $setup->addAttributeGroup($entityTypeId, $attributeSetId, self::ATTR_GROUP_TOP_MENU_PRODUCTS_LABEL, 777);

            foreach ($this->attributes as $attribute => $data) {
                $setup->addAttributeToGroup($entityTypeId, $attributeSetId, self::ATTR_GROUP_TOP_MENU_PRODUCTS_LABEL, $attribute, $data['sort_order']);
            }
        }

        return $setup;
    }
}
