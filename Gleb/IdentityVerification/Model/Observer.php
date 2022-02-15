<?php
namespace Gleb\IdentityVerification\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Observer implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    protected $uploaderFactory;

    protected $fileSystem;

    protected $fileId = 'identity_verification';
    protected $allowedExtensions = ['jpg', 'png'];

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        UploaderFactory $uploaderFactory,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
        Filesystem $fileSystem
    )
    {
        $this->_request = $request;
        $this->customerRepository = $customerRepository;
        $this->uploaderFactory = $uploaderFactory;
        $this->_customer = $customer;
        $this->_customerFactory = $customerFactory;
        $this->fileSystem = $fileSystem;
    }

    public function execute(\Magento\Framework\Event\Observer $observer){

        $observerCustomer = $observer->getEvent()->getCustomer();
        $customerId = $observerCustomer->getId();
        $customer = $this->customerRepository->getById($customerId);

        $fileName = NULL;

        //get files uploaded
        if (isset($_FILES[$this->fileId]['name']) && $_FILES[$this->fileId]['name'] != "") {
            $destinationPath = $this->getDestinationPath() . 'customer/';
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => $this->fileId])
                    ->setAllowCreateFolders(true)
                    ->setAllowRenameFiles(true);
//                    ->setAllowedExtensions($this->allowedExtensions);

                if (!$fileData = $uploader->save($destinationPath)) {
                    throw new LocalizedException(
                        __('File cannot be saved to path: $1', $destinationPath)
                    );
                } else {
                    $fileName = '/' . $fileData['file'];
                }
            } catch (\Exception $e) {
                $message = $this->messageManager->addError(
                    __($e->getMessage())
                );
                return $this;
            }
        } else {
            return $this;
        }

        $observerCustomer->setCustomAttribute('identity_verification', $fileName);
        $this->customerRepository->save($observerCustomer);

        return $this;
    }

    public function getDestinationPath()
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->getAbsolutePath('/');
    }
}
