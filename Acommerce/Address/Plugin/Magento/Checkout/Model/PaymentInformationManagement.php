<?php

namespace Acommerce\Address\Plugin\Magento\Checkout\Model;

class PaymentInformationManagement
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

    public function beforeSavePaymentInformation(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        if (!empty($extAttributes)) {
            $this->helper->transportFieldsFromExtensionAttributesToObject(
                $extAttributes,
                $address,
                'extra_checkout_billing_address_fields'
            );
        }

    }
}