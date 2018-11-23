<?php

namespace Acommerce\Address\Observer\Sales;

class ModelServiceQuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
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
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $this->helper->transportFieldsFromExtensionAttributesToObject(
            $quote->getBillingAddress(),
            $order->getBillingAddress(),
            'extra_checkout_billing_address_fields'
        );

        $this->helper->transportFieldsFromExtensionAttributesToObject(
            $quote->getShippingAddress(),
            $order->getShippingAddress(),
            'extra_checkout_shipping_address_fields'
        );

        $orderBilling = $order->getBillingAddress();
        $orderShipping = $order->getShippingAddress();
        if ($orderBilling && $orderShipping) {
            if (!$orderBilling->getCityId()) {
                $orderBilling->setCityId($orderShipping->getCityId());
            }
            if (!$orderBilling->getTownship()) {
                $orderBilling->setTownship($orderShipping->getTownship());
            }
            if (!$orderBilling->getTownshipId()) {
                $orderBilling->setTownshipId($orderShipping->getTownshipId());
            }
        }
    }
}