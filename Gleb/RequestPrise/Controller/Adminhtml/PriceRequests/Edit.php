<?php

namespace Gleb\RequestPrise\Controller\Adminhtml\PriceRequests;

use Magento\Backend\App\Action;

class Edit extends Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();

        $contactDatas = $this->getRequest()->getParam('contact');
        if(is_array($contactDatas)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ACL RULE HERE');
    }
}
