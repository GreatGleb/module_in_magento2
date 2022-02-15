<?php

namespace Gleb\IdentityVerification\Controller\Adminhtml\Customer\Image;

use Gleb\IdentityVerification\Model\FileUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Agorae Adminhtml Category Image Upload Controller
 */
class Upload extends Action implements HttpPostActionInterface
{
    /**
     * Image uploader
     *
     * @var FileUploader
     */
    protected $fileUploader;

    /**
     * Upload constructor.
     *
     * @param Context $context
     * @param FileUploader $fileUploader
     */
    public function __construct(
        Context $context,
        FileUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
    }

    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed() {
        return true;
//        return $this->_authorization->isAllowed('Gleb_IdentityVerification::customer');
    }
    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $imageId = $this->_request->getParam('param_name', 'customer[image]');

        try {
            $result = $this->fileUploader->saveFileToTmpDir($imageId);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
