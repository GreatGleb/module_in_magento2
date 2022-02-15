<?php


namespace Gleb\CustomerStatus\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\DataObject;

class CustomSection extends DataObject implements SectionSourceInterface
{
    public function getSectionData()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("\Magento\Customer\Model\SessionFactory");

        $customerId = $customerSession->create()->getId();
        if($customerId == NULL) {
            return '';
        }

        $customerRepositoryInterface = $objectManager->create("\Magento\Customer\Api\CustomerRepositoryInterface");

        $customer = $customerRepositoryInterface->getById($customerId);
        $customer_status = $customer->getCustomAttribute('customer_attribute_status')->getValue();

        return [
            'msg' =>$customer_status,
        ];
    }
}
