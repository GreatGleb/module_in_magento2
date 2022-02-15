<?php

namespace Gleb\RequestPrise\Controller\Adminhtml\PriceRequests;

use Magento\Backend\App\Action;
use Gleb\RequestPrise\Model\RequestPrise;

class MassDelete extends Action
{
  public function execute()
  {
    $ids = $this->getRequest()->getParam('selected', []);
    if (!is_array($ids) || !count($ids)) {
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
    foreach ($ids as $id) {
        if ($attributesupport = $this->_objectManager->create(RequestPrise::class)->load($id)) {
            $attributesupport->delete();
        }
    }
    $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($ids)));

    $resultRedirect = $this->resultRedirectFactory->create();
    return $resultRedirect->setPath('*/*/index', array('_current' => true));
  }
}
