<?php

class TM_Testimonials_Block_Widget_List extends Mage_Core_Block_Template
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
            'TM_TESTIMONIALS_WIDGET_LIST',
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
            $template = 'tm/testimonials/widget/list.phtml';
        }
        return $template;
    }

    public function getWidgetConfig()
    {
        $testimonials = $this->getTestimonials();
        return json_encode(array(
                'numTestimonials' => count($testimonials),
                'viewTime' => $this->getViewTime(),
                'animDuration' => $this->getAnimDuration()
            ));
    }

    public function getTestimonials() {
        if (!$this->hasData('testimonials')) {
           $testimonials = Mage::getModel('tm_testimonials/data')->getCollection()
                ->addFieldToFilter('status', TM_Testimonials_Model_Data::STATUS_ENABLED)
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('widget', 1);

            $testimonials->getSelect()
                        ->order(new Zend_Db_Expr('RAND()'))
                        ->limit($this->getItemsNumber());

            $this->setData('testimonials', $testimonials);
        }
        return $this->getData('testimonials');
    }

}