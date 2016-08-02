<?php

class TM_Testimonials_Block_Widget_ListFull extends TM_Testimonials_Block_List_Content
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
            'TM_TESTIMONIALS_WIDGET_LISTFULL',
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
            $template = 'tm/testimonials/list/content.phtml';
        }
        return $template;
    }

    protected function _beforeToHtml() {
        $titleBlock = $this->getLayout()
            ->createBlock('testimonials/list_title')
            ->setTemplate('tm/testimonials/list/title.phtml')
            ->setShowTitle($this->getShowTitle());

        $bottomBlock = $this->getLayout()
            ->createBlock('testimonials/list_bottom')
            ->setTemplate('tm/testimonials/list/bottom.phtml');

        $this->setChild('testimonials.list.title', $titleBlock);
        $this->setChild('testimonials.list.bottom', $bottomBlock);

        return parent::_beforeToHtml();
    }

    public function getShowTitle()
    {
        return (bool) $this->_getData('show_title');
    }
}
