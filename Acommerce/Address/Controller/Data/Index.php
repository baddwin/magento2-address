<?php

namespace Acommerce\Address\Controller\Data;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Acommerce\Address\Helper\Data
     */
    protected $resultJsonFactory;

     /**
     * @var \Acommerce\Address\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Acommerce\Address\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Acommerce\Address\Helper\Data $helper
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
    }

    public function execute()
    {
        $postdata = $this->getRequest()->getParams();
        $result = [
            // 'city' => $this->helper->getCityJson(),
            'township' => $this->helper->getTownshipDataProvider()
        ];
        $json = $this->resultJsonFactory->create();
        return $json->setData($result);
    }
}