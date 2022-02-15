<?php

namespace Gleb\Searchresults\Model\ResourceModel\Searchresults;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Search result Resource Model Collection
 *
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Gleb\Searchresults\Model\Searchresults', 'Gleb\Searchresults\Model\ResourceModel\Searchresults');
    }
}
