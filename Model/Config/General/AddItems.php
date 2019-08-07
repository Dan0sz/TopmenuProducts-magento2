<?php
/**
 * @author: Daan van den Bergh
 * @url: https://daan.dev
 * @package: Dan0sz/TopMenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopMenuProducts\Model\Config\General;

use Magento\Framework\Option\ArrayInterface;

class AddItems implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        // @codingStandardsIgnoreStart
        $options = [
            ['value' => 'after', 'label' => __('After category items')],
            ['value' => 'before', 'label' => __('Before category items')]
        ];

        // @codingStandardsIgnoreEnd
        return $options;
    }
}
