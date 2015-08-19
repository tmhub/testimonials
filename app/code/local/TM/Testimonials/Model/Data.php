<?php
class TM_Testimonials_Model_Data extends Mage_Core_Model_Abstract
{
    /**
     * Testimonial's Statuses
     */
    const STATUS_AWAITING_APPROVAL = 1;
    const STATUS_ENABLED = 2;
    const STATUS_DISABLED = 3;

    const IMAGE_PATH = '/testimonials/pictures/';

    const CACHE_TAG = 'testimonials_data';
    protected $_cacheTag = self::CACHE_TAG;

    public function __construct()
    {
        $this->_init('tm_testimonials/data');
        parent::_construct();
    }

    /**
     * Prepare testimonial's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        $statuses = new Varien_Object(array(
            self::STATUS_AWAITING_APPROVAL => Mage::helper('testimonials')->__('Awaiting approval'),
            self::STATUS_ENABLED => Mage::helper('cms')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('cms')->__('Disabled')
        ));

        Mage::dispatchEvent('testimonials_data_get_available_statuses', array('statuses' => $statuses));

        return $statuses->getData();
    }
    /**
     * Get cache tags associated with object id
     *
     * @return array
     */
    public function getCacheIdTags()
    {
        $tags   = parent::getCacheIdTags();
        $tags[] = 'TM_TESTIMONIAL_' . $this->getTestimonialId();
        return $tags;
    }
}