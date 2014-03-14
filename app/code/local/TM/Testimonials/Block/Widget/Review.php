<?php

class TM_Testimonials_Block_Widget_Review extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    public function getTemplate() 
    {
        $template = parent::getTemplate();
        if (!$template) {
            $template = 'tm/testimonials/widget/review.phtml';
        }
        return $template;
    }

    protected function _beforeToHtml()
    {
        $testimonials = Mage::getModel('tm_testimonials/data')->getCollection()
            ->addFieldToFilter('rating', array("gt" => 0));

        if ($testimonials) {
            $total = 0;
            foreach ($testimonials as $testimonial) {
                $total += (int)$testimonial->getRating();
            }
            $avgRating = $total / $testimonials->getSize();
        }
        $this->setTestimonials($testimonials);
        $this->setAvgRating(number_format($avgRating, 2));

        return parent::_beforeToHtml();
    }

    protected function _toHtml()
    {
        if ($this->getTestimonials()) {
            return parent::_toHtml();
        }
        return '';
    }
}