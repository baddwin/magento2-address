<?php

namespace Acommerce\Address\Plugin\Magento\Quote\Model;

class ShippingAddressManagement
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

    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        if (!empty($extAttributes)) {
            $this->helper->transportFieldsFromExtensionAttributesToObject(
                $extAttributes,
                $address,
                'extra_checkout_shipping_address_fields'
            );
        }
    }
}