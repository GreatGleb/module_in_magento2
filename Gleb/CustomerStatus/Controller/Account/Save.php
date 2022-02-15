<?php

namespace Gleb\CustomerStatus\Controller\Account;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Save extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;


    protected $customerSession;
    protected $customerSessionModel;
    protected $customerModel;
    protected $_request;

    protected $_customerRepositoryInterface;

    /** @var ManagerInterface **/
    protected $messageManager;
    protected $resultRedirectFactory;

    /**
     * @param Context                                             $context
     * @param PageFactory                                         $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \Magezon\CustomerAttachments\Helper\Data            $dataHelper
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Checkout\Model\Session $customerSessionModel,
        \Magento\Customer\Model\Customer $customerModel,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        array $data = []
    ) {
        $this->_request = $request;
        $this->resultPageFactory    = $resultPageFactory;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory       = $resultRedirectFactory;
        $this->customerSession       = $customerSession;
        $this->customerSessionModel       = $customerSessionModel;
        $this->customerModel         = $customerModel;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $status = $this->_request->getParams()["status"];
        $customerSession = $this->customerSession->create();
        $customerId = $customerSession->getId();

        $customer = $this->_customerRepositoryInterface->getById($customerId);
        $customer->setCustomAttribute('customer_attribute_status', $status);
        $this->_customerRepositoryInterface->save($customer);

        $this->messageManager->addSuccessMessage(__('Status was saved.'));
        return $this->resultRedirectFactory->create()->setPath('customerstatus/account/status');
    }
}
