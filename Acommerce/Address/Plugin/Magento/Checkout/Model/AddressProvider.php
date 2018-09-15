<?php

namespace Acommerce\Address\Plugin\Magento\Checkout\Model;

class AddressProvider
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Model\Address
     */
    protected $addressFactory;

    /**
     * @var \Acommerce\Address\Helper\Data
     */
    protected $helperData;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\AddressFactory $addressFactory
     * @param \Acommerce\Address\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Acommerce\Address\Helper\Data $helperData
    ) {
        $this->customerSession = $customerSession;
        $this->addressFactory = $addressFactory;
        $this->helperData = $helperData;
    }

    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        $result
    ) {
        if($this->customerSession->isLoggedIn()){
            $additionalFields = $this->helperData->getExtraCheckoutAddressFields();
            foreach ($result['customerData']['addresses'] as $key => $address) {
                $addressData = $this->addressFactory->create()->load($address['id']);
                foreach ($additionalFields as $field) {
                    $result['customerData']['addresses'][$key][$field] = $addressData->getData($field);
                }
            }
        }
        return $result;
    }
}