<?php

namespace Gleb\IdentityVerification\Model\Customer\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\ObjectManagerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;

class File extends AbstractBackend
{
    const ADDITIONAL_DATA_PREFIX = '_additional_data_';

    protected $_filesystem;

    protected $_fileUploaderFactory;

    private $imageUploader;
    protected $_logger;

    private $objectManager;

    protected $request;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_filesystem = $filesystem;
        $this->request = $request;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger = $logger;
        $this->objectManager = $objectManager;
    }

    private function getImageUploader()
    {
        if($this->imageUploader === null) {
            $this->imageUploader = $this->objectManager->get(\Magento\Catalog\CategoryImageUpload::class);
        }
        return $this->imageUploader;
    }

    protected function getUploadedImageName($value)
    {
        if(is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }

        return '';
    }

    public function beforeSave($object)
    {
        $customer_params = $this->request->getParam('customer[image]');

        $attributeName = $this->getAttribute()->getName();
        $value = array();
        if(isset($customer_params['identity_verification'])) {
            $value = $customer_params['identity_verification'];
            if($imageName = $this->getUploadedImageName($value)) {
                $object->setData(self::ADDITIONAL_DATA_PREFIX . $attributeName, $value);
                $object->setData($attributeName, $imageName);
            }
        } elseif(count($customer_params) > 0) {
            $object->setData($attributeName, NULL);
        }

        return parent::beforeSave($object);
    }

    public function afterSave($object)
    {
        $value = $object->getData(self::ADDITIONAL_DATA_PREFIX . $this->getAttribute()->getName());
        if($imageName = $this->getUploadedImageName($value)) {
            try {
                $this->getImageUploader()->moveFileFromTmp($imageName);
            } catch(\Exception $e) {
                $this->_logger->critical($e);
            }
        }
        return $this;
    }


}
