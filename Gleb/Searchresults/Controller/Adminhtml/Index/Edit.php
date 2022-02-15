<?php

namespace Gleb\Searchresults\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Gleb\Searchresults\Model\Searchresults as Searchresult;

class Edit extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();

        $contactDatas = $this->getRequest()->getParam('contact');
        if(is_array($contactDatas)) {
            // $contact = $this->_objectManager->create(Searchresult::class);
            // $contact->setData($contactDatas)->save();
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ACL RULE HERE');
    }
}
