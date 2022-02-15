<?php

namespace Gleb\Searchresults\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Gleb\Searchresults\Model\Searchresults;

class Delete extends \Magento\Backend\App\Action
{
  public function execute()
  {
    $id = $this->getRequest()->getParams()["id"];

    if (!isset($id)) {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }

    if ($searchresult = $this->_objectManager->create(Searchresults::class)->load($id)) {
        $searchresult->delete();
    }
    $this->messageManager->addSuccess(__('One record have been deleted.'));

    $resultRedirect = $this->resultRedirectFactory->create();
    return $resultRedirect->setPath('*/*/index', array('_current' => true));
  }
}
