<?php

class TM_Testimonials_Block_List_Bottom extends Mage_Core_Block_Template
{
    protected function _beforeToHtml()
    {
        $testimonials = $this->getLayout()
            ->getBlock('testimonials.list.content')
            ->getTestimonials();
        
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