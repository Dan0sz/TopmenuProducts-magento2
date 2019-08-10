<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev
 * @package  : Dan0sz/TopmenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopmenuProducts\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const ATTR_TOPMENU_PRODUCT_ENABLED             = 'topmenu_product_enabled';

    const ATTR_TOPMENU_PRODUCT_LABEL               = 'topmenu_product_label';

    const ATTR_TOPMENU_PRODUCT_SORT_ORDER          = 'topmenu_product_sort_order';

    const ATTR_TOPMENU_PRODUCT_IS_HOME             = 'topmenu_product_is_home';

    const XPATH_TOPMENU_PRODUCTS_GENERAL_ADD_ITEMS = 'topmenu_products/general/add_items';

    /** @var ScopeConfigInterface $scopeConfig */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param      $config
     * @param null $store
     *
     * @return mixed
     */
    public function getConfigValue($config, $store = null)
    {
        return $this->scopeConfig->getValue(
            $config,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
