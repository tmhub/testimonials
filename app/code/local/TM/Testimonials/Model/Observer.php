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
     * @return TM_Testimonials_Model_Observer
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
    /**
     * Send notification for admin about new testimonial added
     *
     * @param Varien_Event_Observer $observer
     * @return TM_Testimonials_Model_Observer
     */
    public function sendNotificationToAdmin(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('testimonials')->isAdminNotificationEnabled()) {
            return $this;
        }

        $emailData = $observer->getEvent()->getTestimonial();
        $adminEmail = Mage::helper('testimonials')->getAdminEmail();
        $subject = Mage::helper('testimonials')->getAdminEmailSubject();
        $templateId = Mage::helper('testimonials')->getAdminEmailTemplate();
        $senderId = Mage::helper('testimonials')->getAdminNotificationSendFrom();

        $storeId = Mage::app()->getStore()->getId();
        $image = $emailData['image'] ? Mage::helper('testimonials')->__("Yes") :
            Mage::helper('testimonials')->__("No");
        $statuses = Mage::getModel('tm_testimonials/data')->getAvailableStatuses();
        $status = $statuses[isset($emailData['status']) ? $emailData['status'] : TM_Testimonials_Model_Data::STATUS_AWAITING_APPROVAL];
        $vars = array(
            'admin_subject' => $subject,
            'user_name' => $emailData['name'],
            'user_email' => $emailData['email'],
            'message' => $emailData['message'],
            'company' => isset($emailData['company']) ? $emailData['company'] : '',
            'website' => isset($emailData['website']) ? $emailData['website'] : '',
            'facebook' => isset($emailData['facebook']) ? $emailData['facebook'] : '',
            'twitter' => isset($emailData['twitter']) ? $emailData['twitter'] : '',
            'rating' => isset($emailData['rating']) ? $emailData['rating'] : -1,
            'image' =>  $image,
            'status' => $status,
            'store_view' => Mage::app()->getStore()->getFrontendName()
        );

        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);

        $mailTemplate = Mage::getModel('core/email_template');
        $mailTemplate->setTemplateSubject($subject)
            ->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
            ->sendTransactional(
                $templateId,
                $senderId,
                $adminEmail,
                Mage::helper('testimonials')->__('Store Administrator'),
                $vars
        );

        $translate->setTranslateInline(true);

        return $this;
    }

    /**
     * Add export button on product review edit form
     *
     * @param Varien_Event_Observer $observer
     * @return TM_Testimonials_Model_Observer
     */
    public function addReviewExportButton(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('testimonials')->isEnabled()) {
            return $this;
        }

        $reviewId = Mage::app()->getRequest()->getParam('id');
        $review = Mage::getModel('review/review')->load($reviewId);
        if (!$review->getCustomerId()) {
            return $this;
        }

        $layout = Mage::app()->getLayout();
        $contentBlock = $layout->getBlock('content');
        $contentBlockChildren = $contentBlock->getSortedChildren();
        foreach ($contentBlock->getChild() as $child) {
            if ($child->getType() != 'adminhtml/review_edit') {
                continue;
            }
            $child->addButton('copy', array(
                'label'     => Mage::helper('testimonials')->__('Copy to Testimonials'),
                'onclick'   => 'setLocation(\'' . $this->getCopyButtonUrl() . '\')',
                'class'     => 'save'
            ), 1);
            break;
        }

        return $this;
    }

    /**
     * Add mass export action in product reviews grid
     *
     * @param Varien_Event_Observer $observer
     * @return TM_Testimonials_Model_Observer
     */
    public function addReviewExportMassAction(Varien_Event_Observer $observer)
    {
        if (!Mage::helper('testimonials')->isEnabled()) {
            return $this;
        }

        $block = $observer->getEvent()->getBlock();
        if (get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
            && $block->getRequest()->getControllerName() == 'catalog_product_review')
        {
            $block->addItem('testimonials', array(
                'label' => Mage::helper('testimonials')->__('Copy to Testimonials'),
                'url' => $this->getMassCopyActionUrl()
            ));
        }
    }

    /**
     * Retrieve the URL for button click
     *
     * @return string
     */
    private function getCopyButtonUrl()
    {
        return Mage::helper("adminhtml")->getUrl(
            '*/testimonials_index/saveReview',
            array('_current' => true)
        );
    }

    /**
     * Retrieve the URL for mass action
     *
     * @return string
     */
    private function getMassCopyActionUrl()
    {
        return Mage::helper("adminhtml")->getUrl(
            '*/testimonials_index/massSaveReview',
            array('_current' => true)
        );
    }

    /**
     * Register Testimonial Form
     *
     * @param Varien_Event_Observer $observer
     * @return TM_Testimonials_Model_Observer
     */
    public function registerTestimonialForm(Varien_Event_Observer $observer)
    {
        $observer->getCollection()
            ->addItem(
                new TM_Gdpr_Model_PersonalDataForm(array(
                    'id' => 'tm:submit_testimonial',
                    'name' => 'TM: Submit Testimonial',
                    'action' => 'tm_testimonials_index_post',
                    'js_config' => array(
                        'form' => 'form[action*="testimonials/index/post"]',
                    )
                ))
            );

        return $this;
    }
}
