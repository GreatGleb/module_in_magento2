<?php

namespace Gleb\Searchresults\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Gleb\Searchresults\Model\Searchresults;

class MassDelete extends \Magento\Backend\App\Action
{
  public function execute()
  {
    $ids = $this->getRequest()->getParam('selected', []);
    if (!is_array($ids) || !count($ids)) {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
    foreach ($ids as $id) {
        if ($searchresult = $this->_objectManager->create(Searchresults::class)->load($id)) {
            $searchresult->delete();
        }
    }
    $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($ids)));

    $resultRedirect = $this->resultRedirectFactory->create();
    return $resultRedirect->setPath('*/*/index', array('_current' => true));
  }
}
