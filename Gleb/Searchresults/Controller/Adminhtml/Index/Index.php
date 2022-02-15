<?php

namespace Gleb\Searchresults\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
	protected $_pageFactory;

	public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

	public function execute()
	{
        $this->_view->loadLayout();
        $this->_view->renderLayout();
	}
}
