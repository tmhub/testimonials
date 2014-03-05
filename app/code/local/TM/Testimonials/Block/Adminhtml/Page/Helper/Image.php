<?php

class TM_Testimonials_Block_Adminhtml_Page_Helper_Image extends Varien_Data_Form_Element_Image
{
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::getBaseUrl('media')
                . TM_Testimonials_Model_Data::IMAGE_PATH
                . $this->getValue();
        }
        return $url;
    }
}
