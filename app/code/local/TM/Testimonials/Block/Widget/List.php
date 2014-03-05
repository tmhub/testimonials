<?php

class TM_Testimonials_Block_Widget_List extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    public function getTemplate() 
    {
        $template = parent::getTemplate();
        if (!$template) {
            $template = 'tm/testimonials/widget/list.phtml';
        }
        return $template;
    }

    protected function _beforeToHtml()
    {
        $testimonial = Mage::getModel('tm_testimonials/data')->getCollection()
            ->addFieldToFilter('status', TM_Testimonials_Model_Data::STATUS_ENABLED)
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('widget', 1);

        $testimonial->getSelect()->order(new Zend_Db_Expr('RAND()'))->limit(1);
        $this->setTestimonial($testimonial->getFirstItem());
        return parent::_beforeToHtml();
    }

    protected function _toHtml()
    {
        if ($this->getTestimonial() && $this->getTestimonial()->getWidget()) {
            return parent::_toHtml();
        }
        return '';
    }
}