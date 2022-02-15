<?php

namespace Gleb\RequestPrise\Block\Adminhtml\RequestPrise\Edit;

class GenericButton
{

    protected $urlBuilder;
    protected $registry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    public function getId()
    {
        $price_request = $this->registry->registry('price_request');
        return $price_request ? $price_request->getId() : null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
