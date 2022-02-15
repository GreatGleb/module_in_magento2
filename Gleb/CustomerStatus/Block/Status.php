<?php

namespace Gleb\CustomerStatus\Block;

class Status extends \Magento\Framework\View\Element\Template
{
    protected $customerModel;
    protected $customerSession;
    protected $customerSessionModel;
    protected $_customerRepositoryInterface;
    protected $request;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Checkout\Model\Session $customerSessionModel,
        \Magento\Customer\Model\Customer $customerModel,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    )
    {
        $this->customerModel         = $customerModel;
        $this->customerSession       = $customerSession;
        $this->customerSessionModel       = $customerSessionModel;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    public function getStatus()
    {
        $customerId = $this->customerSession->create()->getId();
        if($customerId == NULL) {
            return '';
        }

        $customer = $this->_customerRepositoryInterface->getById($customerId);
        $customer_status = $customer->getCustomAttribute('customer_attribute_status')->getValue();

        return $customer_status;
    }

}
