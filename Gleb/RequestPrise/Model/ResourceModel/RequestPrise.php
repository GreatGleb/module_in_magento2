<?php

namespace Gleb\RequestPrise\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class RequestPrise extends AbstractDb
{
    public function _construct()
    {
        $this->_init('price_requests', 'id');
    }
}
