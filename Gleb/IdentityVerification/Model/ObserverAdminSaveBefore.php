<?php
namespace Gleb\IdentityVerification\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Gleb\IdentityVerification\Model\FileUploader;

class ObserverAdminSaveBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    protected $uploaderFactory;

    protected $fileSystem;
    protected $_customerSession;

    protected $fileId = 'identity_verification';
    protected $allowedExtensions = ['jpg', 'png'];

    /**
     * @var FileUploader
     */
    protected $fileUploader;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        UploaderFactory $uploaderFactory,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
        Filesystem $fileSystem,
        FileUploader $fileUploader,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Request\Http $requestHttp
    )
    {
        $this->_request = $request;
        $this->request = $requestHttp;
        $this->customerRepository = $customerRepository;
        $this->uploaderFactory = $uploaderFactory;
        $this->_customer = $customer;
        $this->_customerFactory = $customerFactory;
        $this->fileSystem = $fileSystem;
        $this->_customerSession = $customerSession;
        $this->fileUploader = $fileUploader;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $customerId = $customer->getId();
        $isSaved = $this->_customerSession->getIsSaved();
        $isWasSaved = 0;

        if(!is_array($isSaved)) {
            $array = [];
            $array[$customerId] = 1;
            $this->_customerSession->setIsSaved($array);
        } else {
            if(!array_key_exists($customerId, $isSaved)) {
                $array = [];
                $array[$customerId] = 1;
                $this->_customerSession->setIsSaved($array);
            } else {
                $array = [];
                $this->_customerSession->setIsSaved($array);
                $isWasSaved = 1;
            }
        }

        if(!$isWasSaved) {
            $image = $this->request->getPost()->customer['image'][0]['file'];

            if($image != NULL) {
                $filePath = \Gleb\IdentityVerification\Model\File::BASE_DIR;
                $path = $this->fileUploader->moveFileFromTmp($image, $filePath);

                $image = '/' . $image;

                $customer = $this->customerRepository->getById($customerId);

                $customer->setCustomAttribute('identity_verification', $image);
                $this->customerRepository->save($customer);
            }
        }
    }

    public function getDestinationPath()
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->getAbsolutePath('/');
    }
}
