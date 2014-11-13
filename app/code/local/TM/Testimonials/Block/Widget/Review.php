<?php

class TM_Testimonials_Block_Widget_Review extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    /**
     * Initialize block's cache
     */
    protected function _construct()
    {
        parent::_construct();

        $this->addData(array(
            'cache_lifetime' => false,
            'cache_tags'     => array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Block::CACHE_TAG)
        ));
    }

    /**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
            'TM_TESTIMONIALS_WIDGET_REVIEW',
            Mage::app()->getStore()->getId(),
            (int)Mage::app()->getStore()->isCurrentlySecure(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            $this->getTemplate()
        );
    }

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