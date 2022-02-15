<?php

namespace Gleb\RequestPrise\Controller\Adminhtml\PriceRequests;

use Magento\Backend\App\Action;
use Gleb\RequestPrise\Model\RequestPrise;

class Delete extends Action
{
  public function execute()
  {
    $id = $this->getRequest()->getParams()["id"];

    if (!isset($id)) {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }

    if ($request_price = $this->_objectManager->create(RequestPrise::class)->load($id)) {
        $request_price->delete();
    }
    $this->messageManager->addSuccess(__('One record have been deleted.'));

    $resultRedirect = $this->resultRedirectFactory->create();
    return $resultRedirect->setPath('*/*/index', array('_current' => true));
  }
}
