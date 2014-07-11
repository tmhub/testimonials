<?php

class TM_Testimonials_Block_List_Bottom extends Mage_Core_Block_Template
{
    protected function _beforeToHtml()
    {
        if ($this->getLayout()->getBlock('testimonials.list.content')) {
            $testimonials = $this->getLayout()
                ->getBlock('testimonials.list.content')
                ->getTestimonials();
        } else {
            $testimonials = Mage::getModel('tm_testimonials/data')->getCollection()
                ->addFieldToSelect('*')
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', TM_Testimonials_Model_Data::STATUS_ENABLED)
                ->setOrder('date', 'desc')
                ->setPageSize($this->getPerPage())
                ->setCurPage($this->getCurrentPage());
        }

        $this->setTestimonials($testimonials);
        return parent::_beforeToHtml();
    }

    public function getPerPage()
    {
        $perPage = $this->getData('per_page');
        if (!$perPage) {
            $perPage = Mage::helper('testimonials')->getTestimonialsPerPage();
        }
        return $perPage;
    }
}