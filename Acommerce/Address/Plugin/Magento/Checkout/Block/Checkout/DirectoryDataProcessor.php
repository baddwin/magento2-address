<?php

namespace Acommerce\Address\Plugin\Magento\Checkout\Block\Checkout;

class DirectoryDataProcessor
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

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\DirectoryDataProcessor $subject,
        $result
    ) {
        $result['components']['checkoutProvider']['dictionaries']['city_id'] = $this->helper->getCityDataProvider();
        // $result['components']['checkoutProvider']['dictionaries']['township_id'] = $this->helper->getTownshipDataProvider();
        return $result;
    }
}