<?php

namespace Gleb\Searchresults\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Search result Resource Model
 *
 */
class Searchresults extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('search_request', 'id');
    }
}
