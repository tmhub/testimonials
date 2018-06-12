<?php
class TM_Testimonials_Adminhtml_Testimonials_IndexController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('templates_master/testimonials_index/index')
            ->_addBreadcrumb(
                Mage::helper('testimonials')->__('Testimonials'),
                Mage::helper('testimonials')->__('Manage Testimonials')
            );
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Templates Master'))
             ->_title($this->__('Testimonials'))
             ->_title($this->__('Manage Testimonials'));

        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        // the same form is used to create and edit
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Templates Master'))
             ->_title($this->__('Testimonials'))
             ->_title($this->__('Manage Testimonials'));

        $id = $this->getRequest()->getParam('testimonial_id');
        $model = Mage::getModel('tm_testimonials/data');

        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('cms')->__('This page no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Testimonial'));

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }

        Mage::register('testimonials_data', $model);

        $this->_initAction()
            ->_addBreadcrumb(
                $id ? Mage::helper('testimonials')->__('Edit Testimonial')
                    : Mage::helper('testimonials')->__('New Testimonial'),
                $id ? Mage::helper('testimonials')->__('Edit Testimonial')
                    : Mage::helper('testimonials')->__('New Testimonial'));

        $this->renderLayout();
    }
    /**
     * Save action
     */
    public function saveAction()
    {
        if (!$data = $this->getRequest()->getPost('testimonials')) {
            $this->_redirect('*/*/');
            return;
        }

        $model = Mage::getModel('tm_testimonials/data');
        if ($id = $this->getRequest()->getParam('testimonial_id')) {
            $model->load($id);
        }

        $model->addData($data);

        try {
            if ($uploader = @Mage::getModel('tmcore/image_uploader')) {
                $mediaDir = TM_Testimonials_Model_Data::IMAGE_PATH;
                $uploader->setDirectory($mediaDir)
                    ->setFilesDispersion(true)
                    ->upload($model, 'image');
            } else {
                throw new Exception(
                    Mage::helper('tmcore')->__(
                        "We can't upload image. Update TM Core module."
                    )
                );
            }

            $date = Mage::app()->getLocale()->date($data['date'], Zend_Date::DATE_SHORT, null, false);
            $model->setDate($date->toString('YYYY-MM-dd HH:mm:ss'));

            $model->save();

            // clear testimonials list block cache after new item was added
            Mage::app()->cleanCache(array('tm_testimonials_list'));

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('testimonials')->__('Testimonial has been saved.')
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('testimonial_id' => $model->getId(), '_current' => true));
                return;
            }
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_getSession()->setFormData($data);
        $this->_redirect('*/*/edit', array('testimonial_id' => $this->getRequest()->getParam('testimonial_id'), '_current'=>true));
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('testimonial_id')) {
            try {
                $model = Mage::getModel('tm_testimonials/data');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('testimonials')->__('The testimonial has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('testimonial_id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('testimonials')->__('Unable to find a testimonial to delete.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $testimonialsIds = $this->getRequest()->getParam('testimonials');
        if (!is_array($testimonialsIds)) {
            $this->_getSession()->addError($this->__('Please select testimonial(s).'));
        } else {
            if (!empty($testimonialsIds)) {
                try {
                    foreach ($testimonialsIds as $testimonialId) {
                        $testimonial = Mage::getModel('tm_testimonials/data')->load($testimonialId);
                        $testimonial->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($testimonialsIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $testimonialsIds = (array)$this->getRequest()->getParam('testimonials');
        $status     = (int)$this->getRequest()->getParam('status');
        try {
            foreach ($testimonialsIds as $testimonialId) {
                $testimonial = Mage::getModel('tm_testimonials/data')->load($testimonialId);
                $testimonial->setStatus($status)->save();
            }

            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) have been updated.', count($testimonialsIds))
            );
        }
        catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()
                ->addException($e, $this->__('An error occurred while updating the product(s) status.'));
        }

        $this->_redirect('*/*/');
    }

    public function approveAction()
    {
        $model = Mage::getModel('tm_testimonials/data');
        if ($id = $this->getRequest()->getParam('testimonial_id')) {
            $model->load($id);
        }

        try {
            $model->setStatus(TM_Testimonials_Model_Data::STATUS_ENABLED);
            $model->save();

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('testimonials')->__('Testimonial approved.')
            );
            $this->_redirect('*/*/');
            return;
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/edit', array('testimonial_id' => $this->getRequest()->getParam('testimonial_id'), '_current'=>true));
    }

    /**
     * Save product review as testimonial
     */
    public function saveReviewAction()
    {
        $review = Mage::getModel('review/review');
        if ($id = $this->getRequest()->getParam('id')) {
            $review->load($id);
        }

        $model = Mage::getModel('tm_testimonials/data');
        try {
            $this->saveReview($model, $review);

            // clear testimonials list block cache after new item was added
            Mage::app()->cleanCache(array('tm_testimonials_list'));

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('testimonials')->__('Testimonial has been saved.')
            );
            $this->_redirect('*/*/edit', array('testimonial_id' => $model->getId(), '_current' => true));
            return;
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/edit');
    }

    /**
     * Save product reviews as testimonials
     */
    public function massSaveReviewAction()
    {
        $reviewsIds = $this->getRequest()->getParam('reviews');
        $session    = Mage::getSingleton('adminhtml/session');

        if(!is_array($reviewsIds)) {
             $session->addError(Mage::helper('adminhtml')->__('Please select review(s).'));
        } else {
            $exportedCount = 0;
            try {
                foreach ($reviewsIds as $reviewId) {
                    $review = Mage::getModel('review/review')->load($reviewId);
                    if (!$review->getCustomerId()) {
                        continue;
                    } else {
                        $model = Mage::getModel('tm_testimonials/data');
                        $this->saveReview($model, $review);
                        $exportedCount++;
                    }
                }

                // clear testimonials list block cache after new item(s) was added
                Mage::app()->cleanCache(array('tm_testimonials_list'));

                $session->addSuccess(
                    Mage::helper('testimonials')->__('Total of %d testimonial(s) have been created.', $exportedCount)
                );
            } catch (Mage_Core_Model_Exception $e) {
                $session->addError($e->getMessage());
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            } catch (Exception $e) {
                $session->addException($e, Mage::helper('testimonials')->__('An error occurred while exporting review(s).'));
            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * Save review as testimonial
     * @param $model  tm_testimonials/data model
     * @param $review review/review model
     */
    private function saveReview($model, $review)
    {
        $model->setName($review->getNickname());
        $model->setMessage($review->getDetail());
        $model->setStoreId($review->getStoreId());
        $model->setDate($review->getCreatedAt());

        $customerEmail = Mage::getModel('customer/customer')
            ->load($review->getCustomerId())
            ->getEmail();
        $model->setEmail($customerEmail);

        $ratingSummary = Mage::getModel('rating/rating')
            ->getReviewSummary($review->getId());
        $rating = ceil($ratingSummary->getSum() / $ratingSummary->getCount());
        $rating = round(5 * ($rating / 100));
        $model->setRating($rating);

        $model->save();
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'new':
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('templates_master/testimonials/testimonials/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('templates_master/testimonials/testimonials/delete');
                break;
            case 'approve':
                return Mage::getSingleton('admin/session')->isAllowed('templates_master/testimonials/testimonials/approve');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('templates_master/testimonials/testimonials');
                break;
        }
    }
}
