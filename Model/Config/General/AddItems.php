<?php

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
