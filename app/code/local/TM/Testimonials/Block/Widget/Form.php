<?php

class TM_Testimonials_Block_Widget_Form extends TM_Testimonials_Block_Form_Form
    implements Mage_Widget_Block_Interface
{
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
        $this->setChild(
            'testimonials.captcha',
            $this->getLayout()->createBlock('captcha/captcha', 'captcha')
                ->setFormId('testimonials_form')
                ->setImgWidth(230)
                ->setImgHeight(50)
        );
        return parent::_prepareLayout();
    }
}