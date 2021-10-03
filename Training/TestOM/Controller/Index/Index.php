<?php

namespace Training\TestOM\Controller\Index;

use Training\TestOM\Model\PlayWithTest;
use Training\TestOM\Model\Test;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $test;
    protected $playWithTest;

    public function __construct(
        Test $test,
        PlayWithTest $playWithTest,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->test = $test;
        $this->playWithTest = $playWithTest;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
//        $this->test->log();
        $this->playWithTest->run();
    }
}
