<?php
namespace Gleb\IdentityVerification\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class ObserverProfileSaveBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    protected $uploaderFactory;
    protected $_customerSession;

    protected $fileSystem;

    protected $fileId = 'identity_verification';
    protected $allowedExtensions = ['jpg', 'png'];

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        UploaderFactory $uploaderFactory,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        Filesystem $fileSystem
    )
    {
        $this->_request = $request;
        $this->customerRepository = $customerRepository;
        $this->uploaderFactory = $uploaderFactory;
        $this->_customer = $customer;
        $this->_customerFactory = $customerFactory;
        $this->fileSystem = $fileSystem;
        $this->_customerSession = $customerSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer){
        $itisregistration = $this->_request->getPost()->itisregistration;
        if($itisregistration == 1) {
            return $this;
        }

        $fileName = NULL;

        $observerCustomer = $observer->getEvent()->getCustomer();

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

        if($fileName != NULL) {
            $observerCustomer->setData('identity_verification', $fileName);
        }

        return $this;
    }

    public function getDestinationPath()
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->getAbsolutePath('/');
    }
}
