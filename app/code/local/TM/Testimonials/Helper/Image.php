<?php

class TM_Testimonials_Helper_Image extends Mage_Core_Helper_Abstract
{

    protected $_image;

    public function init($image, $mode = 'image')
    {
        $this->_image = $image;
        return $this;
    }

    public function resize($width, $height)
    {
        if (!$imageUrl = $this->getImageUrl()) {
            return '';
        }

        $dir = Mage::getBaseDir('media')
            . DS . "testimonials"
            . DS . "pictures"
            . DS . "resized";

        if (!file_exists($dir)) {
            mkdir($dir, 0777);
        };

        $imageName = substr(strrchr($imageUrl, "/"), 1);
        $imageName = $width . '_' . $height . '_' . $imageName;

        $imageResized = $dir . DS . $imageName;

        $imagePath = str_replace(Mage::getBaseUrl('media'), 'media/', $imageUrl);
        $imagePath = Mage::getBaseDir() . DS . str_replace("/", DS, $imagePath);

        if (!file_exists($imageResized) && file_exists($imagePath)) {
            $imageObj = new Varien_Image($imagePath);
            $imageObj->constrainOnly(true);
            $imageObj->keepAspectRatio(true);
            $imageObj->keepFrame(false);
            $imageObj->keepTransparency(true);
            $imageObj->resize($width, $height);
            $imageObj->save($imageResized);
        }
        $imageUrl = Mage::getBaseUrl('media')
            . "testimonials/pictures/resized/"
            . $imageName;

        return $imageUrl;
    }

    public function getImageUrl()
    {
        $image = $this->_image;
        if (empty($image)) {
            return false;
        }
        return Mage::getBaseUrl('media')
            . 'testimonials/pictures/'
            . trim($image, '/');
    }
}
