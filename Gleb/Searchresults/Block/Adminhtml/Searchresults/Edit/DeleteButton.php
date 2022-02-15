<?php

namespace Gleb\Searchresults\Block\Adminhtml\Searchresults\Edit;

use Magento\Backend\Block\Widget\Context;
use \Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        UrlInterface $urlInterface
    ) {
        $this->context = $context;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];

        $id = $this->context->getRequest()->getParam('id');

        if ($id) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this search result?')
                    . '\', \'' . $this->getDeleteUrl($id) . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl($id)
    {
        return $this->_urlInterface->getUrl('*/*/delete', ['id' => $id]);
    }
}
