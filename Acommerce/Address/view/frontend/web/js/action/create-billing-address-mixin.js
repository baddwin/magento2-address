define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';
    return function (setBillingAddressAction) {
        return wrapper.wrap(setBillingAddressAction, function (originalAction, messageContainer) {
            var customAttributes = {};
            if (messageContainer.city_id !== undefined) {
                customAttributes['city_id'] = {'attribute_code':'city_id', 'value': messageContainer.city_id};
            }
            if (messageContainer.township !== undefined) {
                customAttributes['township'] = {'attribute_code':'township', 'value': messageContainer.township};
            }
            if (messageContainer.township_id !== undefined) {
                customAttributes['township_id'] = {'attribute_code':'township_id', 'value': messageContainer.township_id};
            }
            messageContainer['custom_attributes'] = customAttributes;

            return originalAction(messageContainer);
        });
    };
});