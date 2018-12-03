var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-billing-address': {
                'Acommerce_Address/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'Acommerce_Address/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'Acommerce_Address/js/action/create-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'Acommerce_Address/js/action/create-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Acommerce_Address/js/action/place-order-mixin': true
            },
            'Magento_Customer/js/model/customer/address': {
                'Acommerce_Address/js/model/customer/address-mixin': true
            }
        }
    },
    map: {
        '*': {
            cityUpdater: 'Acommerce_Address/js/city-updater',
            townshipUpdater: 'Acommerce_Address/js/township-updater'
        }
    }
};