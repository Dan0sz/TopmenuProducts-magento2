<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev
 * @package  : Dan0sz/TopmenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopmenuProducts\Model;

use Magento\Catalog\Model\AbstractModel;

class Config extends AbstractModel
{
    const ATTR_TOPMENU_PRODUCT_ENABLED    = 'topmenu_product_enabled';

    const ATTR_TOPMENU_PRODUCT_LABEL      = 'topmenu_product_label';

    const ATTR_TOPMENU_PRODUCT_SORT_ORDER = 'topmenu_product_sort_order';

    const ATTR_TOPMENU_PRODUCT_IS_HOME    = 'topmenu_product_is_home';
}
