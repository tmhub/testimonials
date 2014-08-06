<?php

class TM_Testimonials_Block_Adminhtml_Page_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $model = Mage::registry('testimonials_data');
        $form  = new Varien_Data_Form();
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('testimonials')->__('Testimonial Information'),
            'class' => 'fieldset-wide'
        ));

        if ($model->getTestimonialId()) {
            $fieldset->addField('testimonial_id', 'hidden', array(
                'name'  => 'testimonial_id'
            ));
        }

        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $fieldset->addField('name', 'text', array(
            'name'     => 'name',
            'label'    => Mage::helper('catalog')->__('Name'),
            'title'    => Mage::helper('catalog')->__('Name'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('email', 'text', array(
            'name'     => 'email',
            'label'    => Mage::helper('contacts')->__('Email'),
            'title'    => Mage::helper('contacts')->__('Email'),
            'required' => true,
            'class'    => 'validate-email',
            'disabled' => $isElementDisabled
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'     => 'stores[]',
                'label'    => Mage::helper('cms')->__('Store View'),
                'title'    => Mage::helper('cms')->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled' => $isElementDisabled
            ));
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'  => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('message', 'textarea', array(
            'name'     => 'message',
            'label'    => Mage::helper('testimonials')->__('Message'),
            'title'    => Mage::helper('testimonials')->__('Message'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('company', 'text', array(
            'name'     => 'company',
            'label'    => Mage::helper('testimonials')->__('Company'),
            'title'    => Mage::helper('testimonials')->__('Company'),
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('website', 'text', array(
            'name'     => 'website',
            'label'    => Mage::helper('testimonials')->__('Website'),
            'title'    => Mage::helper('testimonials')->__('Website'),
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('facebook', 'text', array(
            'name'     => 'facebook',
            'label'    => Mage::helper('testimonials')->__('Facebook'),
            'title'    => Mage::helper('testimonials')->__('Facebook'),
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('twitter', 'text', array(
            'name'     => 'twitter',
            'label'    => Mage::helper('testimonials')->__('Twitter'),
            'title'    => Mage::helper('testimonials')->__('Twitter'),
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('rating', 'select', array(
            'name'     => 'rating',
            'label'    => Mage::helper('testimonials')->__('Rating'),
            'title'    => Mage::helper('testimonials')->__('Rating'),
            'options'  => array('-1' => Mage::helper('testimonials')->__('No rating'),
                                '1' => '1 ' . Mage::helper('testimonials')->__('star'),
                                '2' => '2 ' . Mage::helper('testimonials')->__('stars'),
                                '3' => '3 ' . Mage::helper('testimonials')->__('stars'),
                                '4' => '4 ' . Mage::helper('testimonials')->__('stars'),
                                '5' => '5 ' . Mage::helper('testimonials')->__('stars')),
            'disabled' => $isElementDisabled
        ));

        $this->_addElementTypes($fieldset); //register own image element
        $fieldset->addField('image', 'image', array(
            'name'     => 'image',
            'label'    => Mage::helper('catalog')->__('Profile image'),
            'title'    => Mage::helper('catalog')->__('Profile image'),
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('status', 'select', array(
            'name'     => 'status',
            'label'    => Mage::helper('cms')->__('Status'),
            'title'    => Mage::helper('cms')->__('Status'),
            'options'  => $model->getAvailableStatuses(),
            'disabled' => $isElementDisabled,
            'required' => true
        ));

        $fieldset->addField('widget', 'select', array(
            'name'     => 'widget',
            'label'    => Mage::helper('testimonials')->__('Display in widget'),
            'title'    => Mage::helper('testimonials')->__('Display in widget'),
            'options'  => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
            'disabled' => $isElementDisabled,
            'value'    => 1
        ));

        $fieldset->addField('date', 'date', array(
            'name'     => 'date',
            'label'    => Mage::helper('testimonials')->__('Created date'),
            'title'    => Mage::helper('testimonials')->__('Created date'),
            'image'    => $this->getSkinUrl('images/grid-cal.gif'),
            'format'   => Mage::app()->getLocale()->getDateFormatWithLongYear(),
            'disabled' => $isElementDisabled
        ));

        $form->addValues($model->getData());
        $form->setFieldNameSuffix('testimonials');
        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function _getAdditionalElementTypes()
    {
        return array(
            'image' => Mage::getConfig()->getBlockClassName('testimonials/adminhtml_page_helper_image')
        );
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('testimonials')->__('Testimonial Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('testimonials')->__('Testimonial Information');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
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
}