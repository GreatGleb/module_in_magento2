<?php

namespace Gleb\RequestPrise\Controller\Request;

use Gleb\RequestPrise\Model\RequestPrise;
use Magento\Framework\App\Action\Action;

class Price extends Action
{
    protected $requestPrise;
	protected $request;
	protected $messageManager;

	public function __construct(
        RequestPrise $requestPrise,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Action\Context $context)
    {
        $this->requestPrise      = $requestPrise;
        $this->messageManager     = $messageManager;
        $this->request      = $request;
        $this->context           = $context;
        return parent::__construct($context);
    }

	public function execute()
	{
        $data = $this->context->getRequest()->getParams();
        $data = (array)json_decode($data['request']);

        $this->requestPrise->setData($data);

        $isSuccess = 1;

        try {
            $this->requestPrise->save();
        } catch (\Exception $e) {
            $this->messageManager->addMessage(new \Magento\Framework\Message\Error(__("Sorry, Unfortunately, an error occurred. Please try again later.")));
            $isSuccess = 0;
        }

        if($isSuccess) {
            $this->messageManager->addMessage(new \Magento\Framework\Message\Success(__('This message confirms your request was delivered successfully and we will be in touch shortly.')));
        }

        echo 1;
	}
}
