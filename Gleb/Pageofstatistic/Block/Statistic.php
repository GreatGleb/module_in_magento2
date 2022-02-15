<?php

namespace Gleb\Pageofstatistic\Block;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as SalesCollection;

class Statistic extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customerModel;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $customerSession;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $customerSessionModel;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepositoryInterface;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var SalesCollection
     */
    protected $productCollection;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @param \Magento\Backend\Block\Template\Context           $context
     * @param \Magento\Customer\Model\SessionFactory            $customerSession
     * @param \Magento\Checkout\Model\Session                   $customerSessionModel
     * @param \Magento\Customer\Model\Customer                  $customerModel
     * @param SalesCollection                                   $productCollection
     * @param \Magento\Sales\Api\OrderRepositoryInterface       $orderRepository
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param \Magento\Framework\App\Request\Http               $request
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Checkout\Model\Session $customerSessionModel,
        \Magento\Customer\Model\Customer $customerModel,
        SalesCollection $productCollection,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    )
    {
        $this->customerModel         = $customerModel;
        $this->customerSession       = $customerSession;
        $this->customerSessionModel       = $customerSessionModel;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->productCollection = $productCollection;
        $this->orderRepository = $orderRepository;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    public function getStatistic()
    {
        $customerId = $this->customerSession->create()->getId();
        if($customerId == NULL) {
            return 0;
        }
        $statistic = $this->productCollection->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id',$customerId)
            ->getData();

        $subtotal = 0;
        $shipping = 0;
        $total = 0;

        $orders = [];
        foreach ($statistic as $order) {
            if(!array_key_exists($order['entity_id'], $orders)) {
                $orders[$order['entity_id']] = [
                    "created_at" => $order["created_at"],
                    "status" => $order["status"],
                    "total_price_of_products" => $order["grand_total"],
                    "total_price_of_shipping" => $order["shipping_amount"],
                    "total_due" => $order["total_due"]
                ];
                $subtotal += (float)$order["grand_total"];
                $shipping += (float)$order["shipping_amount"];
                $total += (float)$order["total_due"];
            }
        }

        foreach ($orders as $order_id=>$order) {
            $items = $this->orderRepository->get($order_id)->getItems();
            foreach ($items as $item) {
                $orders[$order_id]["products"][] = [
                    "product_name" => $item->getName(),
                    "product_price" => $item->getPriceInclTax(),
                    "quantity_of_products" => $item->getQtyOrdered(),
                    "total_price" => $item->getRowTotalInclTax(),
                ];
            }
        }

        $statistic = [$orders, [$subtotal, $shipping, $total]];

        return $statistic;
    }

}
