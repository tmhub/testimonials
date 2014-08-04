<?php

class TM_Testimonials_Model_Resource_Data extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('tm_testimonials/data','testimonial_id');
    }

    /**
     * Perform operations before object save
     *
     * @param Mage_Cms_Model_Block $object
     * @return TM_Testimonials_Model_Resource_Data
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId() && !$object->getDate()) {
            $object->setDate(Mage::getSingleton('core/date')->gmtDate());
        }
        return $this;
    }

    /**
     * Assign page to store views
     *
     * @param Mage_Core_Model_Abstract $object
     * @return TM_Testimonials_Model_Resource
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('tm_testimonials/store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'testimonial_id = ?'    => (int) $object->getId(),
                'store_id IN (?)'       => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'testimonial_id'    => (int) $object->getId(),
                    'store_id'          => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return TM_Testimonials_Model_Resource_Data
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param TM_Testimonials_Model_Data $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('store' => $this->getTable('tm_testimonials/store')),
                $this->getMainTable() . '.testimonial_id = store.testimonial_id',
                array())
                ->where('status = ?', 1)
                ->where('store.store_id IN (?)', $storeIds)
                ->order('store.store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($testimonialId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('tm_testimonials/store'), 'store_id')
            ->where('testimonial_id = ?',(int)$testimonialId);

        return $adapter->fetchCol($select);
    }
}