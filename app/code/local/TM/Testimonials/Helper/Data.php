<?php
class TM_Testimonials_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Path to store config if frontend output is enabled
     *
     * @var string
     */
    const XML_PATH_ENABLED            = 'testimonials/general/enabled';
    /**
     * Path to store config where count of news posts per page is stored
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE     = 'testimonials/general/items_per_page';
    /**
     * Path to store config testimonial image width
     *
     * @var string
     */
    const XML_PATH_IMAGE_W            = 'testimonials/general/image_width';
    /**
     * Path to store config testimonial image height
     *
     * @var string
     */
    const XML_PATH_IMAGE_H            = 'testimonials/general/image_height';

    /**
     * Checks whether testimonials can be displayed in the frontend
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store);
    }
    /**
     * Return the number of items per page
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getTestimonialsPerPage($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_ITEMS_PER_PAGE, $store));
    }
    /**
     * Return testimonial image width
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getImageWidth($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_IMAGE_W, $store));
    }
    /**
     * Return testimonial image height
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getImageHeight($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_IMAGE_H, $store));
    }
}