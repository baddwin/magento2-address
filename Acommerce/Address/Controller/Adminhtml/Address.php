<?php

namespace Acommerce\Address\Controller\Adminhtml;

abstract class Address extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Acommerce\Address\Model\RegionFactory
     */
    protected $regionFactory;

    /**
     * @var \Acommerce\Address\Model\CityFactory
     */
    protected $cityFactory;

    /**
     * @var \Acommerce\Address\Model\TownshipFactory
     */
    protected $townshipFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Acommerce\Address\Model\RegionFactory $regionFactory
     * @param \Acommerce\Address\Model\CityFactory $cityFactory
     * @param \Acommerce\Address\Model\TownshipFactory $townshipFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Acommerce\Address\Model\RegionFactory $regionFactory,
        \Acommerce\Address\Model\CityFactory $cityFactory,
        \Acommerce\Address\Model\TownshipFactory $townshipFactory
    ) {
        $this->filter = $filter;
        $this->coreRegistry = $coreRegistry;
        $this->csvProcessor = $csvProcessor;
        $this->resource = $resource;
        $this->resultRawFactory = $resultRawFactory;
        $this->fileFactory = $fileFactory;
        $this->directoryList = $directoryList;
        $this->regionFactory = $regionFactory;
        $this->cityFactory = $cityFactory;
        $this->townshipFactory = $townshipFactory;
        parent::__construct($context);
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magento_Customer::customer');
        return $resultPage;
    }
}
