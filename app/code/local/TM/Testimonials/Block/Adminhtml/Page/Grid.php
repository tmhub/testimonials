<?php
class TM_Testimonials_Block_Adminhtml_Page_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('testimonialsGrid');
        $this->setDefaultSort('testimonial_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tm_testimonials/data')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('testimonial_id', array(
            'header'   => Mage::helper('testimonials')->__('Id'),
            'index'    => 'testimonial_id',
            'width'    => 50
        ));

        $this->addColumn('name', array(
            'header'   => Mage::helper('testimonials')->__('Name'),
            'index'    => 'name'
        ));

        $this->addColumn('email', array(
            'header'   => Mage::helper('testimonials')->__('Email'),
            'index'    => 'email'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition')
            ));
        }

        $this->addColumn('date', array(
            'header'   => Mage::helper('testimonials')->__('Created Date'),
            'index'    => 'date',
            'width'    => 150,
            'type' => 'datetime'
        ));

        $this->addColumn('status', array(
            'header'   => Mage::helper('testimonials')->__('Status'),
            'index'    => 'status',
            'type'     => 'options',
            'width'    => 150,
            'options'  => Mage::getSingleton('tm_testimonials/data')->getAvailableStatuses()
        ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('testimonial_id');
        $this->getMassactionBlock()->setFormFieldName('testimonials');

        if ($this->_isAllowedAction('delete')) {
            $this->getMassactionBlock()->addItem('delete', array(
                 'label'=> Mage::helper('catalog')->__('Delete'),
                 'url'  => $this->getUrl('*/*/massDelete'),
                 'confirm' => Mage::helper('catalog')->__('Are you sure?')
            ));
        }

        if ($this->_isAllowedAction('approve')) {
            $statuses = Mage::getSingleton('tm_testimonials/data')->getAvailableStatuses();

            array_unshift($statuses, array('label'=>'', 'value'=>''));
            $this->getMassactionBlock()->addItem('status', array(
                 'label'=> Mage::helper('catalog')->__('Change status'),
                 'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                 'additional' => array(
                        'visibility' => array(
                             'name' => 'status',
                             'type' => 'select',
                             'class' => 'required-entry',
                             'label' => Mage::helper('catalog')->__('Status'),
                             'values' => $statuses
                         )
                 )
            ));
        }
        
        return $this;
    }

    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('testimonial_id' => $row->getTestimonialId()));
    }
    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('templates_master/testimonials/testimonials/' . $action);
    }
}