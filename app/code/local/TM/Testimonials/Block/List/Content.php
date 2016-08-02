<?php

class TM_Testimonials_Block_List_Content extends Mage_Core_Block_Template
{
    protected $_placeholderImage;
    protected $_testimonialsCollection;
    /**
     * Initialize block's cache
     */
    protected function _construct()
    {
        parent::_construct();

        $this->addData(array(
            'cache_lifetime' => false,
            'cache_tags'     => array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Block::CACHE_TAG, 'tm_testimonials_list')
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
    /**
     * Retrieve loaded category collection
     *
     * @return TM_Testimonials_Model_Resource_Data_Collection
     */
    protected function _getTestimonialsCollection()
    {
        if (null === $this->_testimonialsCollection) {
            $this->_testimonialsCollection = Mage::getModel('tm_testimonials/data')->getCollection()
                ->addFieldToSelect('*')
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', TM_Testimonials_Model_Data::STATUS_ENABLED)
                ->setOrder('date', 'desc')
                ->setPageSize($this->getPerPage())
                ->setCurPage($this->getCurrentPage());
        }
        return $this->_testimonialsCollection;
    }

    protected function _beforeToHtml()
    {
        $testimonials = $this->_getTestimonialsCollection();
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
    /**
     * Retrieve block cache tags based on options collection
     *
     * @return array
     */
    public function getCacheTags()
    {
        return array_merge(
            parent::getCacheTags(),
            $this->getItemsTags($this->_getTestimonialsCollection())
        );
    }
    /**
     * Copied for 1.7 compatibility
     *
     * Collect and retrieve items tags.
     * Item should implements Mage_Core_Model_Abstract::getCacheIdTags method
     *
     * @param array|Varien_Data_Collection $items
     * @return array
     */
    public function getItemsTags($items)
    {
        $tags = array();
        /** @var $item Mage_Core_Model_Abstract */
        foreach($items as $item) {
            $itemTags = $item->getCacheIdTags();
            if (false === $itemTags) {
                continue;
            }
            $tags = array_merge($tags, $itemTags);
        }
        return $tags;
    }
}
