<?php

namespace Gleb\Editaddress\Plugin\Checkout;

class LayoutProcessorPlugin
{
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */

    public function aroundProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        \Closure $proceed,
        array $jsLayout
    )
    {
        $jsLayoutResult = $proceed($jsLayout);

        $shippingAddress = &$jsLayoutResult['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        $label = $shippingAddress['street']['label'];
        unset($shippingAddress['street']['label']);

        // Shipping fields street labels
        $shippingAddress['street']['children']['0']['label'] = $label;
        $shippingAddress['street']['children']['1']['label'] = __('Adresszusatz');

        return $jsLayoutResult;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    )
    {
        $shippingAddress = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children']['0']['label'];
        $shippingAddressPopUpForm = &$jsLayout['components']['checkout']['children']['steps']['children'];

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'])) {
            foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'] as $key => $payment) {
                if (isset($payment['children']['form-fields']['children']['street']['children'])) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']['street']['children']['0']['label'] = __($shippingAddress);
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']['street']['children']['1']['label'] = __('Adresszusatz');
                }
            }

        }

        return $jsLayout;
    }
}
