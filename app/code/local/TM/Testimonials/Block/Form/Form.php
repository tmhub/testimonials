<?php
class TM_Testimonials_Block_Form_Form extends Mage_Core_Block_Template
{
    public function getUserName()
    {
        if ($data = $this->_getSessionData('name')) {
            return $data;
        }
        return $this->helper('contacts')->getUserName();
    }

    public function getUserEmail()
    {
        if ($data = $this->_getSessionData('email')) {
            return $data;
        }
        return $this->helper('contacts')->getUserEmail();
    }

    public function getUserMessage()
    {
        if ($data = $this->_getSessionData('message')) {
            return $data;
        }
        return '';
    }

    public function getCompany()
    {
        if ($data = $this->_getSessionData('company')) {
            return $data;
        }
        return '';
    }

    public function getWebSite()
    {
        if ($data = $this->_getSessionData('website')) {
            return $data;
        }
        return '';
    }

    public function getTwitter()
    {
        if ($data = $this->_getSessionData('twitter')) {
            return $data;
        }
        return '';
    }

    public function getFacebook()
    {
        if ($data = $this->_getSessionData('facebook')) {
            return $data;
        }
        return '';
    }

    public function checkRating($rating)
    {
        if ($data = $this->_getSessionData('rating')) {
            return ($data == $rating);
        }
        return false;
    }

    protected function _getSessionData($key)
    {
        $data = Mage::getSingleton('customer/session')->getTestimonialsFormData();
        if ($data && isset($data[$key])) {
            return $data[$key];
        }
        return false;
    }
}