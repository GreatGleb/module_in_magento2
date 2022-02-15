<?php

namespace Gleb\RequestPrise\Model\ResourceModel\RequestPrise;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init('Gleb\RequestPrise\Model\RequestPrise', 'Gleb\RequestPrise\Model\ResourceModel\RequestPrise');
    }
}
