<?php

namespace Acommerce\Address\Plugin\Magento\Checkout\Block\Checkout;

class LayoutProcessor
{
    /**
     * @var \Acommerce\Address\Helper\Data
     */
    protected $helper;

    /**
     * @param \Acommerce\Address\Helper\Data $helper
     */
    public function __construct(
        \Acommerce\Address\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $result
    ) {
        $result = $this->getShippingFormFields($result);
        $result = $this->getBillingFormFields($result);
        return $result;
    }

    protected function getShippingFormFields($result) {
        $shippingFieldset = $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset'];
        if(isset($shippingFieldset)){
            $shippingCustomFields = $this->getFields('shippingAddress','shipping');

            $shippingFields = $shippingFieldset['children'];
            if(isset($shippingFields['street'])){
                unset($shippingFields['street']['children'][1]['validation']);
                unset($shippingFields['street']['children'][2]['validation']);
            }
            if(isset($shippingFields['township'])){
                unset($shippingFields['township']);
            }
            $shippingFields = array_replace_recursive($shippingFields, $shippingCustomFields);
            
            $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $shippingFields;
        }

        $result['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['city']['sortOrder'] = 108;
        $result['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['city']['visible'] = false;

        if ($this->helper->getStoreConfigValue('checkout/checkout_address/disable_country', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE)) {
            $result['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']
            ['children']['country_id']['visible'] = false;
        }

        return $result;
    }

    protected function getBillingFormFields($result) {
        $billingFieldset = $result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list'];
        if (isset($billingFieldset)) {
            $paymentForms = $billingFieldset['children'];
            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);
                if (!isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                    continue;
                }
                $billingFields = $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'];
                $billingCustomFields = $this->getFields('billingAddress' . $paymentMethodCode,'billing');
                $billingFields = array_replace_recursive($billingFields, $billingCustomFields);

                if(isset($billingFields['township'])){
                    unset($billingFields['township']);
                }

                $billingFields['city']['sortOrder'] = 108;
                $billingFields['city']['visible'] = false;

                $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'] = $billingFields;

                if ($this->helper->getStoreConfigValue('checkout/checkout_address/disable_country', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE)) {
                    $result['components']['checkout']['children']['steps']['children']
                    ['billing-step']['children']['payment']['children']['payments-list']['children']
                    [$paymentMethodCode . '-form']['children']['form-fields']['children']['country_id']['visible'] = false;
                }
            }
        }

        return $result;
    }

    protected function getFields($scope, $addressType) {
        $fields = [];
        foreach ($this->getAdditionalFields($addressType) as $field) {
            $fields[$field] = $this->getField($field, $scope, $addressType);
        }
        return $fields;
    }

    protected function getAdditionalFields($addressType = 'shipping') {
        if ($addressType == 'shipping') {
            return $this->helper->getExtraCheckoutAddressFields('extra_checkout_shipping_address_fields');
        }
        return  $this->helper->getExtraCheckoutAddressFields('extra_checkout_billing_address_fields');
    }

    protected function getField($attributeCode, $scope, $addressType = 'shipping') {
        $target = '${ $.provider }:${ $.parentScope }';
        if ($addressType == 'shipping') {
            $target = 'checkoutProvider:shippingAddress';
        }

        $disableTownship = (bool) $this->helper->getStoreConfigValue('checkout/checkout_address/disable_township', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE);
        $field = [];
        if ($attributeCode == 'city_id') {
            $field = [
                'component' => 'Acommerce_Address/js/form/element/city',
                'config' => [
                    'customScope' => $scope,
                    // 'customEntry' => $scope . '.city',
                    'elementTmpl' => 'ui/form/element/select',
                ],
                'validation' => [
                    'required-entry' => true,
                ],
                'filterBy' => [
                    'target' => $target . '.region_id',
                    'field' => 'region_id',
                ],
                'imports' => [
                    'initialOptions' => 'index = checkoutProvider:dictionaries.city_id',
                    'setOptions' => 'index = checkoutProvider:dictionaries.city_id',
                ],
                'deps' => 'checkoutProvider',
                'dataScope' => $scope . '.' . $attributeCode
            ];
        } elseif ($attributeCode == 'township_id') {
            $field = [
                'component' => 'Acommerce_Address/js/form/element/township',
                'config' => [
                    'customScope' => $scope,
                    'customEntry' => $scope . '.township',
                    'elementTmpl' => 'ui/form/element/select',
                ],
                'validation' => [
                    'required-entry' => true,
                ],
                'filterBy' => [
                    'target' => $target . '.city_id',
                    'field' => 'city_id',
                ],
                'imports' => [
                    'initialOptions' => 'index = checkoutProvider:dictionaries.township_id',
                    'setOptions' => 'index = checkoutProvider:dictionaries.township_id',
                ],
                'deps' => 'checkoutProvider',
                'dataScope' => $scope . '.' . $attributeCode,
                'hidden' => $disableTownship
            ];
        } elseif ($attributeCode == 'township') {
            $field = [
                'config' => [
                    'customScope' => $scope,
                ],
                'validation' => [
                    'required-entry' => true,
                ],
                'dataScope' => $scope . '.' . $attributeCode,
                'hidden' => $disableTownship
            ];
        }
        return $field;
    }
}