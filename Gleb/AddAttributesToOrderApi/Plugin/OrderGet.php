<?php

namespace Gleb\AddAttributesToOrderApi\Plugin;

class OrderGet
{
    protected $_orderExtensionFactory;

    public function __construct(
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->_orderExtensionFactory = $orderExtensionFactory;
    }

    public function afterGet(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        $extensionAttributes = $order->getExtensionAttributes();
        $orderExtension = $extensionAttributes ? $extensionAttributes : $this->_orderExtensionFactory->create();

        $test1 = "test1";
        $test2 = "test2";
        $orderExtension->setTest1($test1);
        $orderExtension->setTest2($test2);

        $order->setExtensionAttributes($orderExtension);

        return $order;
    }

}
