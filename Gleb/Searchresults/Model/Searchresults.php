<?php

namespace Gleb\Searchresults\Model;

use Magento\Cron\Exception;
use Magento\Framework\Model\AbstractModel;

/**
 * Searchresult Model
 *
 */
class Searchresults extends AbstractModel
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $_dateTime;

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Gleb\Searchresults\Model\ResourceModel\Searchresults::class);
    }

}
