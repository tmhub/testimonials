<?php
class TM_Testimonials_Model_Observer
{
    /**
     * Get Captcha String
     *
     * @param Varien_Object $request
     * @param string $formId
     * @return string
     */
    protected function _getCaptchaString($request, $formId)
    {
        $captchaParams = $request->getPost(Mage_Captcha_Helper_Data::INPUT_NAME_FIELD_VALUE);
        return $captchaParams[$formId];
    }

    /**
     * Break the execution in case of incorrect CAPTCHA
     *
     * @param Varien_Event_Observer $observer
     * @return Your_Module_Model_Observer
     */
    public function checkCaptcha($observer)
    {
        $formId = 'testimonials_form';
        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        if ($captchaModel->isRequired()) {
            $controller = $observer->getControllerAction();
            if (!$captchaModel->isCorrect($this->_getCaptchaString($controller->getRequest(), $formId))) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                Mage::getSingleton('customer/session')->setTestimonialsFormData($controller->getRequest()->getPost());
                $url = Mage::helper('core/http')->getHttpReferer() ?
                    Mage::helper('core/http')->getHttpReferer() :
                    Mage::getUrl();
                $controller->getResponse()->setRedirect($url);
            }
        }

        return $this;
    }
}