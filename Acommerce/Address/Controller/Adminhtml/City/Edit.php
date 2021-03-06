<?php

namespace Acommerce\Address\Controller\Adminhtml\City;

class Edit extends \Acommerce\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Acommerce_Address::city_edit';

    /**
     * Edit City page
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // Get ID and create model
        $city_id = (int) $this->getRequest()->getParam('city_id');
        $model = $this->cityFactory->create();
        $model->setData([]);
        // Initial checking
        if ($city_id && $city_id > 0) {
            $model->load($city_id);
            if (!$model->getCityId()) {
                $this->messageManager->addErrorMessage(__('This city no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
            $default_name = $model->getDefaultName();
        }

        $formData = $this->_session->getFormData(true);
        if (!empty($formData)) {
            $model->setData($formData);
        }

        $this->coreRegistry->register('acommerce_address_city', $model);

        // Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $city_id ? __('Edit City') : __('New City'),
            $city_id ? __('Edit City') : __('New City')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Cities Manager'));
        $resultPage->getConfig()->getTitle()->prepend($city_id ? 'Edit: '.$default_name.' ('.$city_id.')' : __('New City'));

        return $resultPage;
    }
}
