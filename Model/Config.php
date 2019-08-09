<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev
 * @package  : Dan0sz/TopMenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopMenuProducts\Model;

use Magento\Catalog\Model\AbstractModel;

class Config extends AbstractModel
{
    const ATTR_TOP_MENU_PRODUCT_ENABLED    = 'top_menu_product_enabled';

    const ATTR_TOP_MENU_PRODUCT_LABEL      = 'top_menu_product_label';

    const ATTR_TOP_MENU_PRODUCT_SORT_ORDER = 'top_menu_product_sort_order';

    const ATTR_TOP_MENU_PRODUCT_IS_HOME    = 'top_menu_product_is_home';
}
