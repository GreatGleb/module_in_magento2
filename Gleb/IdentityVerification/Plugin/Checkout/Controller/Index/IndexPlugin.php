<?php

namespace Gleb\IdentityVerification\Plugin\Checkout\Controller\Index;

class IndexPlugin
{
    /** @var ManagerInterface **/
    protected $messageManager;
    protected $resultRedirectFactory;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
    )
    {
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory       = $resultRedirectFactory;
    }

    public function afterExecute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');

        $customerId = $customerSession->getCustomer()->getId();
        $customerModel = $objectManager->create('\Magento\Customer\Model\Customer');

        $customerData = $customerModel->load($customerId);
        $is_pending = $customerData->getData('is_pending');

        if($is_pending === "0") {
            $this->messageManager->addErrorMessage(__('Your account status is pending review. Please wait while the administrator reviews your account.'));
            return $this->resultRedirectFactory->create()->setPath('/');
        }
    }
}
