<?php


namespace Gleb\AddCoupon\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderAfter implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $event = $observer->getEvent();
        $order = $event->getOrder();
        $ids = $event->getOrderIds();

        $orderData = $order->getData();
        $orderId = $order->getId();
        $orderCoupon = $orderData["coupon_code"];

        if($orderCoupon != NULL) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();

            $sql = "UPDATE sales_order_grid SET coupon_code = " . $orderCoupon . " WHERE sales_order_grid.entity_id = " . $orderId . ";";
            $connection->query($sql);
        }

        return $this;
    }
}
