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
     * Path to store config company field enabled
     *
     * @var string
     */
    const XML_COMPANY_ENABLED            = 'testimonials/general/enable_company';
    /**
     * Path to store config website field enabled
     *
     * @var string
     */
    const XML_WEBSITE_ENABLED            = 'testimonials/general/enable_website';
    /**
     * Path to store config twitter field enabled
     *
     * @var string
     */
    const XML_TWITTER_ENABLED            = 'testimonials/general/enable_twitter';
    /**
     * Path to store config facebook field enabled
     *
     * @var string
     */
    const XML_FACEBOOK_ENABLED            = 'testimonials/general/enable_facebook';
    /**
     * Path to store config sent message
     *
     * @var string
     */
    const XML_SENT_MESSAGE                = 'testimonials/general/sent_message';
    /**
     * Path to store config for placeholder image
     *
     * @var string
     */
    const XML_PLACEHOLDER_IMAGE           = 'testimonials/general/placeholder_image';

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
    /**
     * Checks whether company field enabled
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isCompanyEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_COMPANY_ENABLED, $store);
    }
    /**
     * Checks whether website field enabled
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isWebsiteEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_WEBSITE_ENABLED, $store);
    }
    /**
     * Checks whether twitter field enabled
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isTwitterEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_TWITTER_ENABLED, $store);
    }
    /**
     * Checks whether facebook field enabled
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isFacebookEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_FACEBOOK_ENABLED, $store);
    }
    /**
     * Return testimonial sent message
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return String
     */
    public function getSentMessage($store = null)
    {
        return Mage::getStoreConfig(self::XML_SENT_MESSAGE, $store);
    }
    /**
     * Return testimonial placeholder image
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return String
     */
    public function getPlaceholderImage($store = null)
    {
       return Mage::getStoreConfig(self::XML_PLACEHOLDER_IMAGE, $store);
    }
}