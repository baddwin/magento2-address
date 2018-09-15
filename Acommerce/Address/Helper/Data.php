<?php

namespace Acommerce\Address\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\DataObject\Copy\Config
     */
    protected $fieldsetConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Cache\Type\Config
     */
    protected $configCacheType;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Customer\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $locale;

    /**
     * @var \Acommerce\Address\Model\ResourceModel\City\Collection
     */
    protected $cityCollection;

    /**
     * @var \Acommerce\Address\Model\ResourceModel\Township\Collection
     */
    protected $townshipCollection;

    /**
     * @var string
     */
    private $cityJson;

    /**
     * @var string
     */
    private $townshipJson;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\DataObject\Copy\Config $fieldsetConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonHelper
     * @param \Magento\Customer\Model\AddressFactory $addressFactory
     * @param \Magento\Framework\Locale\ResolverInterface $locale
     * @param \Acommerce\Address\Model\ResourceModel\City\CollectionFactory $cityCollection
     * @param \Acommerce\Address\Model\ResourceModel\Township\CollectionFactory $townshipCollection
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\DataObject\Copy\Config $fieldsetConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Framework\Serialize\Serializer\Json $jsonHelper,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Framework\Locale\ResolverInterface $locale,
        \Acommerce\Address\Model\ResourceModel\City\CollectionFactory $cityCollection,
        \Acommerce\Address\Model\ResourceModel\Township\CollectionFactory $townshipCollection
    ) {
        $this->fieldsetConfig = $fieldsetConfig;
        $this->storeManager = $storeManager;
        $this->configCacheType = $configCacheType;
        $this->jsonHelper = $jsonHelper;
        $this->addressFactory = $addressFactory;
        $this->locale = $locale;
        $this->cityCollection = $cityCollection;
        $this->townshipCollection = $townshipCollection;
        parent::__construct($context);
    }

    public function getExtraCheckoutAddressFields(
        $fieldset='extra_checkout_billing_address_fields',
        $root='global'
    ){
        $fields = $this->fieldsetConfig->getFieldset($fieldset, $root);
        $extraCheckoutFields = [];
        foreach($fields as $field => $fieldInfo){
            $extraCheckoutFields[] = $field;
        }
        return $extraCheckoutFields;
    }

    public function transportFieldsFromExtensionAttributesToObject(
        $fromObject,
        $toObject,
        $fieldset='extra_checkout_billing_address_fields'
    ) {
        foreach($this->getExtraCheckoutAddressFields($fieldset) as $extraField) {
            $set = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));
            $get = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));
            $value = $fromObject->$get();
            try {
                $toObject->$set($value);
            } catch (\Exception $e) {
                $this->_logger->critical($e->getMessage());
            }
        }
        return $toObject;
    }

    public function getCityDataProvider()
    {
        return $this->cityCollection->create()->load()->initLocale($this->locale)->toOptionArray();
    }

    /**
     * Retrieve city data json
     *
     * @return string
     */
    public function getCityJson()
    {
        if (!$this->cityJson) {
            $cacheKey = 'DIRECTORY_CITY_JSON_STORE' . $this->storeManager->getStore()->getId();
            $json = $this->configCacheType->load($cacheKey);
            if (empty($json)) {
                $cities = $this->cityCollection->create()->load()->initLocale($this->locale)->getCityData();
                $json = $this->jsonHelper->serialize($cities);
                if ($json === false) {
                    $json = 'false';
                }
                $this->configCacheType->save($json, $cacheKey);
            }
            $this->cityJson = $json;
        }
        return $this->cityJson;
    }

    public function getTownshipDataProvider()
    {
        return $this->townshipCollection->create()->load()->initLocale($this->locale)->toOptionArray();
    }

    /**
     * Retrieve township data json
     *
     * @return string
     */
    public function getTownshipJson()
    {
        if (!$this->townshipJson) {
            $cacheKey = 'DIRECTORY_TOWNSHIP_JSON_STORE' . $this->storeManager->getStore()->getId();
            $json = $this->configCacheType->load($cacheKey);
            if (empty($json)) {
                $townships = $this->townshipCollection->create()->load()->initLocale($this->locale)->getTownshipData();
                $json = $this->jsonHelper->serialize($townships);
                if ($json === false) {
                    $json = 'false';
                }
                $this->configCacheType->save($json, $cacheKey);
            }
            $this->townshipJson = $json;
        }
        return $this->townshipJson;
    }

    /**
     * Retrieve address data
     *
     * @return string|bool
     */
    public function getAddressData($addressId, $field)
    {
        $addressData = $this->addressFactory->create()->load($addressId);
        if($addressData->getId()){
            return $addressData->getData($field);
        }
        return false;
    }

    /**
     * Retrieve system config value
     *
     * @return string
     */
    public function getStoreConfigValue($configPath, $scope = \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue($configPath, $scope);
    }
}