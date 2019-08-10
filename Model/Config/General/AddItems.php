<?php
/**
 * @author: Daan van den Bergh
 * @url: https://daan.dev
 * @package: Dan0sz/TopmenuProducts
 * @copyright: (c) 2019 Daan van den Bergh
 */

namespace Dan0sz\TopmenuProducts\Model\Config\General;

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
