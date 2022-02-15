<?php

namespace Gleb\RequestPrise\Model\Config\Source;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [['value' => 'New', 'label' => __('New')], ['value' => 'In Progress', 'label' => __('In Progress')], ['value' => 'Closed', 'label' => __('Closed')]];
    }

    public function toArray()
    {
        return [0 => __('New'), 1 => __('In Progress'), 2 => __('Closed')];
    }
}
