<?php

class TM_Testimonials_Model_System_Config_Backend_Image extends Mage_Adminhtml_Model_System_Config_Backend_Image
{
    protected function _beforeSave()
    {
        if ($_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value']) {
            return parent::_beforeSave();
        }

        $value = $this->getValue();
        if (is_array($value) && !empty($value['delete'])) {
            $this->setValue('');
        }
        // fix to save default config value on the first save
        /* else {
            $this->unsValue();
        }*/
        return $this;
    }
}