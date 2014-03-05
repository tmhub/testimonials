<?php

class TM_Testimonials_Block_Adminhtml_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'testimonials';
        $this->_controller = 'adminhtml_page';
        $this->_headerText = Mage::helper('testimonials')->__('Manage Testimonials');

        parent::__construct();
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('testimonials/index/' . $action);
    }
}
