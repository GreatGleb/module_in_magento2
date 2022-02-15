<?php

namespace Gleb\Searchresults\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

use Gleb\Searchresults\Model\SearchresultsFactory;
use Magento\Framework\DB\Ddl\Table;

class Searchresults implements ObserverInterface
{

    private $remoteAddress;
    private $queryFactory;
    private $collection;

    public function __construct(
        \Magento\Search\Model\QueryFactory $queryFactory,
        RemoteAddress $remoteAddress,
        SearchresultsFactory $collection
    ) {
        $this->queryFactory = $queryFactory;
        $this->remoteAddress = $remoteAddress;
        $this->collection = $collection;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $searchTerm = $this->queryFactory->get()->getQueryText();
        $searchNumber = $this->queryFactory->get()->getNumResults();

        $ip = $this->remoteAddress->getRemoteAddress();
        $isregister = 0;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        if($customerSession->isLoggedIn()) {
            $isregister = 1;
        }

        $collection = $this->collection->create();
        $_data = array('id'=>NULL,'text_search'=>$searchTerm,'time_search'=>NULL,
                        'result'=>$searchNumber, 'ip_user'=>$ip, 'registered'=>$isregister);
        $collection->setData($_data)
            ->save();

        return $this;
    }
}
