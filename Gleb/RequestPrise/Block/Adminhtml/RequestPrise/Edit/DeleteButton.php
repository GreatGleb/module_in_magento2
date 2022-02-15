<?php

namespace Gleb\RequestPrise\Block\Adminhtml\RequestPrise\Edit;

use Magento\Backend\Block\Widget\Context;
use \Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    protected $context;

    public function __construct(
        Context $context,
        UrlInterface $urlInterface
    ) {
        $this->context = $context;
        $this->_urlInterface = $urlInterface;
    }

    public function getButtonData()
    {
        $data = [];

        $id = $this->context->getRequest()->getParam('id');

        if ($id) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this price request?')
                    . '\', \'' . $this->getDeleteUrl($id) . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    public function getDeleteUrl($id)
    {
        return $this->_urlInterface->getUrl('*/*/delete', ['id' => $id]);
    }
}
