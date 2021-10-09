<?php

namespace Training\TestOM\Plugin\Model;

class Url
{
    public function beforeGetUrl(
        \Magento\Framework\UrlInterface $subject,
        $routePath = null,
        $routeParams = null
    ) {
        if ($routePath == 'customer/account/create') {
            return ['customer/account/login', null];
        }
    }
}