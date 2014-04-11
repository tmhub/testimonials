<?php

class TM_Testimonials_Block_List_Content extends Mage_Core_Block_Template
{
    protected $_placeholderImage;
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
            'TM_TESTIMONIALS_LIST',
            Mage::app()->getStore()->getId(),
            (int)Mage::app()->getStore()->isCurrentlySecure(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            $this->getTemplate(),
            $this->getPerPage(),
            $this->getCurrentPage()
        );
    }

    protected function _beforeToHtml()
    {
        $testimonials = Mage::getModel('tm_testimonials/data')->getCollection()
            ->addFieldToSelect('*')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', TM_Testimonials_Model_Data::STATUS_ENABLED)
            ->setOrder('date', 'desc')
            ->setPageSize($this->getPerPage())
            ->setCurPage($this->getCurrentPage());
        
        $this->setTestimonials($testimonials);
        $this->_placeholderImage = Mage::helper('testimonials')->getPlaceholderImage();
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

    public function canShowSocial($testimonial)
    {
        return (($testimonial->getFacebook() && Mage::helper('testimonials')->isFacebookEnabled())||
                ($testimonial->getTwitter() && Mage::helper('testimonials')->isTwitterEnabled()));
    }

    public function getImagePath($testimonial) 
    {
        $image = $testimonial->getImage();
        if (!$image && $this->_placeholderImage) {
            $image = $this->_placeholderImage;
        }
        return $image;
    }
}