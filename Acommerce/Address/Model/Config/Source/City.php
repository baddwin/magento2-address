<?php

namespace Acommerce\Address\Model\Config\Source;

/**
 * Options provider for cities list
 */
class City implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Countries
     *
     * @var \Acommerce\Address\Model\ResourceModel\City\Collection
     */
    protected $cityCollection;

    /**
     * @param \Acommerce\Address\Model\ResourceModel\City\Collection $cityCollection
     */
    public function __construct(\Acommerce\Address\Model\ResourceModel\City\Collection $cityCollection)
    {
        $this->cityCollection = $cityCollection;
    }

    /**
     * Options array
     *
     * @var array
     */
    protected $_options;

    /**
     * Return options array
     *
     * @param boolean $isMultiselect
     * @param string|array $foregroundCountries
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = $this->cityCollection->toOptionArray();
        }

        return $this->_options;
    }
}
