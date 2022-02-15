<?php

namespace Gleb\RequestPrise\Model;

use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

class RequestPrise extends AbstractModel
{
    protected $_dateTime;

    protected function _construct()
    {
        $this->_init(\Gleb\RequestPrise\Model\ResourceModel\RequestPrise::class);
    }

}
