<?php

namespace Acommerce\Address\Observer\Config;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class UpdateDefaultCountry implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Config\ReinitableConfigInterface
     */
    protected $appConfig;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    protected $configInterface;

    /**
     * @param \Magento\Framework\App\Config\ReinitableConfigInterface $appConfig
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface
     */
    public function __construct(
        \Magento\Framework\App\Config\ReinitableConfigInterface $appConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface
    ) {
        $this->appConfig = $appConfig;
        $this->scopeConfig = $scopeConfig;
        $this->configInterface = $configInterface;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $website = $observer->getEvent()->getWebsite();
        $scope = ScopeInterface::SCOPE_WEBSITE;
        if (!$website) {
            $website = 0;
            $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
        }
        $disableCountry = $this->scopeConfig->getValue('checkout/checkout_address/disable_country', $scope);
        $defaultCountry = $this->scopeConfig->getValue('checkout/checkout_address/default_country', $scope);
        if ($disableCountry && $defaultCountry) {
            $this->configInterface->saveConfig('general/country/allow', $defaultCountry, $scope, $website);
            $this->configInterface->saveConfig('general/country/default', $defaultCountry, $scope, $website);
            $this->appConfig->reinit();
        }
    }
}
