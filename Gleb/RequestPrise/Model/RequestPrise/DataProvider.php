<?php

namespace Gleb\RequestPrise\Model\RequestPrise;

use Gleb\RequestPrise\Model\ResourceModel\RequestPrise\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    public function __construct(
        \Magento\Catalog\Model\Product $product,
        CollectionFactory $collectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->product = $product;
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $requestPrice) {
            $data = $requestPrice->getData();
            $data['sku'] = $this->product->load($data['entity_id'])->getSku();
            $this->loadedData[$requestPrice->getId()] = $data;
        }

        return $this->loadedData;
    }
}
