<?php

class TM_Testimonials_Block_Widget_Form extends TM_Testimonials_Block_Form_Form
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
            'TM_TESTIMONIALS_WIDGET_FORM',
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
            $template = 'tm/testimonials/form/form.phtml';
        }
        return $template;
    }

    protected function _prepareLayout()
    {
        $head = $this->getLayout()->getBlock('head');
        if ($head) {
            $head->addJs('mage/captcha.js');
        }
        $this->append(
            $this->getLayout()->createBlock('captcha/captcha', 'captcha')
                ->setFormId('testimonials_form')
                ->setImgWidth(230)
                ->setImgHeight(50)
        );
        return parent::_prepareLayout();
    }
}