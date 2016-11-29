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
     * Path to store config of testimonial auto approve
     *
     * @var string
     */
    const XML_PATH_APPROVE            = 'testimonials/general/approve';
    /**
     * Path to store config allow guests to submit testimonials
     *
     * @var string
     */
    const XML_PATH_ALLOW_GUEST_SUBMIT   = 'testimonials/general/allow_guest_submit';
    /**
     * Path to store config for testimonials list layout
     *
     * @var string
     */
    const XML_LIST_LAYOUT           = 'testimonials/list/layout';
    /**
     * Path to store config where count of news posts per page is stored
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE     = 'testimonials/list/items_per_page';
    /**
     * Path to store config testimonial image width
     *
     * @var string
     */
    const XML_PATH_IMAGE_W            = 'testimonials/list/image_width';
    /**
     * Path to store config testimonial image height
     *
     * @var string
     */
    const XML_PATH_IMAGE_H            = 'testimonials/list/image_height';
    /**
     * Path to store config for placeholder image
     *
     * @var string
     */
    const XML_PLACEHOLDER_IMAGE           = 'testimonials/list/placeholder_image';
    /**
     * Path to store config for show user email in list
     *
     * @var string
     */
    const XML_LIST_EMAIL           = 'testimonials/list/show_email';
    /**
     * Path to store config for testimonials form layout
     *
     * @var string
     */
    const XML_FORM_LAYOUT           = 'testimonials/form/layout';
    /**
     * Path to store config starts on form enabled
     *
     * @var string
     */
    const XML_STARS_ENABLED            = 'testimonials/form/enable_stars';
    /**
     * Path to store config company field enabled
     *
     * @var string
     */
    const XML_COMPANY_ENABLED            = 'testimonials/form/enable_company';
    /**
     * Path to store config website field enabled
     *
     * @var string
     */
    const XML_WEBSITE_ENABLED            = 'testimonials/form/enable_website';
    /**
     * Path to store config twitter field enabled
     *
     * @var string
     */
    const XML_TWITTER_ENABLED            = 'testimonials/form/enable_twitter';
    /**
     * Path to store config facebook field enabled
     *
     * @var string
     */
    const XML_FACEBOOK_ENABLED            = 'testimonials/form/enable_facebook';
    /**
     * Path to store config sent message
     *
     * @var string
     */
    const XML_SENT_MESSAGE                = 'testimonials/form/sent_message';
    /**
     * Path to store config admin email notification enable
     *
     * @var string
     */
    const XML_ADMIN_EMAIL_ENABLED                = 'testimonials/email_admin/send_enable';
    /**
     * Path to store config send email for admin from
     *
     * @var string
     */
    const XML_ADMIN_EMAIL_SEND_FROM              = 'testimonials/email_admin/send_from';
    /**
     * Path to store config admin email
     *
     * @var string
     */
    const XML_ADMIN_EMAIL              = 'testimonials/email_admin/admin_email';
    /**
     * Path to store config admin email subject
     *
     * @var string
     */
    const XML_ADMIN_EMAIL_SUBJECT              = 'testimonials/email_admin/email_subject';
    /**
     * Path to store config admin email template
     *
     * @var string
     */
    const XML_ADMIN_EMAIL_TEMPLATE              = 'testimonials/email_admin/email_template';

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
     * Check if enabled stars on form for Rating field
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isStarsOnFormEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_STARS_ENABLED, $store);
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
    /**
     * Checks whether user mail should be displayed in testimonials list
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function showUserEmail($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_LIST_EMAIL, $store);
    }
    /**
    * Return facebook icon path
    * @return String
    */
    public function getFacebookIcon()
    {
        return Mage::getBaseUrl('media') . 'testimonials/pictures/facebook.png';
    }
    /**
    * Return twitter icon path
    * @return String
    */
    public function getTwitterIcon()
    {
        return Mage::getBaseUrl('media') . 'testimonials/pictures/twitter.png';
    }
    /**
    * Return testimonials list layout
    * @return String
    */
    public function getListLayout($store = null)
    {
        return Mage::getStoreConfig(self::XML_LIST_LAYOUT, $store);
    }
    /**
    * Return testimonials form layout
    * @return String
    */
    public function getFormLayout($store = null)
    {
        return Mage::getStoreConfig(self::XML_FORM_LAYOUT, $store);
    }
    /**
     * Checks whether testimonials should be approved automatically
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isAutoApprove($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_APPROVE, $store);
    }
    /**
     * Checks if send email for admin enabled
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isAdminNotificationEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_ADMIN_EMAIL_ENABLED, $store);
    }
    /**
     * Return admin email send from contact
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return String
     */
    public function getAdminNotificationSendFrom($store = null)
    {
        return Mage::getStoreConfig(self::XML_ADMIN_EMAIL_SEND_FROM, $store);
    }
    /**
     * Return admin email
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return String
     */
    public function getAdminEmail($store = null)
    {
        return Mage::getStoreConfig(self::XML_ADMIN_EMAIL, $store);
    }
    /**
     * Return admin email subject
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return String
     */
    public function getAdminEmailSubject($store = null)
    {
        return Mage::getStoreConfig(self::XML_ADMIN_EMAIL_SUBJECT, $store);
    }
    /**
     * Return admin email template
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return String
     */
    public function getAdminEmailTemplate($store = null)
    {
        return Mage::getStoreConfig(self::XML_ADMIN_EMAIL_TEMPLATE, $store);
    }
    /**
     * Checks whether guest is allowed to submit testimonials
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function allowGuestSubmit($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ALLOW_GUEST_SUBMIT, $store);
    }

    /**
     * Escape testimonial data before output
     * @param  [type] $testimonial [description]
     * @return [type]              [description]
     */
    public function escapeTetimonial($testimonial)
    {
        $dataKeys = array_keys($testimonial->getData());
        foreach ($dataKeys as $key) {
            $testimonial->setData($key, $this->escapeHtml($testimonial->getData($key)));
        }
        return $testimonial;
    }
}
