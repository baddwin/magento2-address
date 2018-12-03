<?php

namespace Acommerce\Address\Model\Config\Source;

/**
 * Options provider for regions list
 */
class Region implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Countries
     *
     * @var \Acommerce\Address\Model\ResourceModel\Region\Collection
     */
    protected $regionCollection;

    /**
     * @param \Acommerce\Address\Model\ResourceModel\Region\Collection $regionCollection
     */
    public function __construct(\Acommerce\Address\Model\ResourceModel\Region\Collection $regionCollection)
    {
        $this->regionCollection = $regionCollection;
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
            $this->_options = $this->regionCollection->toOptionArray();
        }

        return $this->_options;
    }
}
