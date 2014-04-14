<?php
class TM_Testimonials_Block_Adminhtml_Page_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {

        $this->_blockGroup = 'testimonials';
        $this->_objectId   = 'testimonial_id';
        $this->_controller = 'adminhtml_page';

        parent::__construct();

        if ($this->_isAllowedAction('save')) {
            $this->_addButton('saveandcontinue', array(
                'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit(\''.$this->_getSaveAndContinueUrl().'\')',
                'class'     => 'save',
            ), -100);
        } else {
            $this->_removeButton('save');
        }
        if (!$this->_isAllowedAction('delete')) {
            $this->_removeButton('delete');
        }

        if ($this->_isAllowedAction('approve')) {
            if (Mage::registry('testimonials_data')->getStatus() == TM_Testimonials_Model_Data::STATUS_AWAITING_APPROVAL) {
                $this->addButton('approve', array(
                    'label'     => Mage::helper('testimonials')->__('Approve'),
                    'onclick'   => "setLocation('" . $this->_getApproveUrl() . "')",
                    'class'     => 'save'
                ));
            }
        }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('testimonials_data')->getId()) {
            return Mage::helper('testimonials')->__("Edit Testimonial");
        }
        else {
            return Mage::helper('testimonials')->__('New Testimonial');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('templates_master/testimonials/testimonials/' . $action);
    }

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current' => true
        ));
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'   => true,
            'back'       => 'edit',
            'active_tab' => '{{tab_id}}'
        ));
    }

    protected function _getApproveUrl()
    {
        return $this->getUrl('*/*/approve', array(
            '_current'   => true
        ));
    }

    protected function _prepareLayout()
    {
        $tabsBlock = $this->getLayout()->getBlock('testimonials_page_edit_tabs');
        if ($tabsBlock) {
            $tabsBlockJsObject = $tabsBlock->getJsObjectName();
            $tabsBlockPrefix   = $tabsBlock->getId() . '_';
        } else {
            $tabsBlockJsObject = 'page_tabsJsTabs';
            $tabsBlockPrefix   = 'page_tabs_';
        }

        $this->_formScripts[] = "
            function saveAndContinueEdit(urlTemplate) {
                var tabsIdValue = " . $tabsBlockJsObject . ".activeTab.id;
                var tabsBlockPrefix = '" . $tabsBlockPrefix . "';
                if (tabsIdValue.startsWith(tabsBlockPrefix)) {
                    tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length)
                }
                var template = new Template(urlTemplate, /(^|.|\\r|\\n)({{(\w+)}})/);
                var url = template.evaluate({tab_id:tabsIdValue});
                editForm.submit(url);
            }
        ";
        return parent::_prepareLayout();
    }
}