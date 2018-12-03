<?php

namespace Acommerce\Address\Controller\Adminhtml\Township;

use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;

class Save extends \Acommerce\Address\Controller\Adminhtml\Address
{
    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (empty($data['township_id'])) {
                $data['township_id'] = null;
            }

            /** @var \Acommerce\Address\Model\Township $model */
            $model = $this->townshipFactory->create();

            $township_id = $this->getRequest()->getParam('township_id');
            if ($township_id) {
                try {
                    $model = $model->load($township_id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This township no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the township.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['township_id' => $model->getTownshipId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the township.'));
            }

            $this->_getSession()->setFormData($data);
            if ($this->getRequest()->getParam('township_id')) {
                return $resultRedirect->setPath('*/*/edit', ['township_id' => $township_id]);
            }
            return $resultRedirect->setPath('*/*/new');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
