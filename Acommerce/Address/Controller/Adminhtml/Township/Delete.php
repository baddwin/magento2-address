<?php

namespace Acommerce\Address\Controller\Adminhtml\Township;

class Delete extends \Acommerce\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Acommerce_Address::township_delete';
    
    /**
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        // check if we know what should be deleted
        $township_id = $this->getRequest()->getParam('township_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($township_id) {
            try {
                // init model and delete
                $model = $this->townshipFactory->create()->load($township_id);
                $township_name = $model->getDefaultName();
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The township %1 has been deleted.', $township_name));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['township_id' => $township_id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('Township to delete was not found.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
