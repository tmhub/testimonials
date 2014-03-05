<?php
	class TM_Testimonials_IndexController extends Mage_Core_Controller_Front_Action 
	{
        /** 
         * Pre dispatch action that allows to redirect to no route page in case 
         * of disabled extension through Admin panel 
         */ 
        public function preDispatch()
        {
            parent::preDispatch();
     
            if (!Mage::helper('testimonials')->isEnabled()) {
                $this->setFlag('', 'no-dispatch', true);
                $this->_redirect('noRoute');
            }
        }

		public function indexAction()
		{
            $layout = $this->loadLayout()->getLayout();
            $isAjax = Mage::app()->getRequest()->isAjax();
            if ($isAjax) {
                $currentPage = (int)$this->getRequest()->getParam('page', 1);
                $output = $layout->getBlock('testimonials.list.content')
                    ->setCurrentPage($currentPage)
                    ->toHtml();
                $this->getResponse()->setBody(
                    Mage::helper('core')->jsonEncode(array('outputHtml' => $output))
                );
            } else {
                $this->getLayout()->getBlock('head')->setTitle(
                    Mage::helper('testimonials')->__('Testimonials')
                );
                $this->renderLayout();
            }
		}

        public function newAction() 
        {
            $this->loadLayout();
            $this->_initLayoutMessages('customer/session');
            $this->_initLayoutMessages('catalog/session');
            $this->getLayout()->getBlock('head')->setTitle(
                Mage::helper('testimonials')->__('New Testimonial')
            ); 
            $this->renderLayout();
        }

        public function testAction()
        {
            $this->_initLayoutMessages('customer/session');
            for ($i=0; $i < 50; $i++) { 
                $model = Mage::getModel('tm_testimonials/data');
                $model->setStoreId(Mage::app()->getStore()->getStoreId());
                $model->setName("Test " . $i);
                $model->setEmail("test" . $i ."@test.com");
                $model->setMessage("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non dui vitae justo porta malesuada. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque sapien ante, volutpat et pulvinar in, pulvinar vel tortor. Fusce sed velit sit amet nisi tincidunt blandit. Vivamus ut lorem eros. In vitae auctor diam. Donec dignissim neque libero, vel ullamcorper tortor porta eget. Nullam placerat, lectus quis volutpat ullamcorper, quam justo mattis eros, sit amet fringilla est nulla sit amet mi. Etiam ultrices libero sit amet nisi porta eleifend. Praesent varius dui a nisl tincidunt bibendum. Duis laoreet dui sit amet libero pellentesque suscipit.");
                $model->setRating(rand(1, 5));

                try {
                    $model->save();
                } catch (Exception $e) {
                    $this->showError($e->getMessage(), $data);
                    return;
                }
            }
            Mage::getSingleton('customer/session')->addSuccess("Test data saved.");
            $this->_redirect('*/*/new');
        }

        public function postAction() 
        {
            $this->_initLayoutMessages('customer/session');
            // check if data sent
            if ($data = $this->getRequest()->getPost()) {
                $model = Mage::getModel('tm_testimonials/data');

                $model->setStoreId(Mage::app()->getStore()->getStoreId());
                $model->setName($data['name']);
                $model->setEmail($data['email']);
                $model->setMessage($data['message']);
                $model->setCompany($data['company']);
                $model->setWebsite($data['website']);
                $model->setTwitter($data['twitter']);
                $model->setFacebook($data['facebook']);
                if (isset($data['rating'])) $model->setRating($data['rating']);

                // upload image
                if (isset($_FILES['image']['name']) && ($_FILES['image']['tmp_name'] != NULL)) {
                    $path = Mage::getBaseDir('media') . TM_Testimonials_Model_Data::IMAGE_PATH;
                    try {
                        $uploader = new Varien_File_Uploader('image');
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(true);
                        $uploader->save($path, $_FILES['image']['name']);
                        $uploadedImg = $uploader->getUploadedFileName();
                        $model->setImage($uploadedImg);
                    } catch (Exception $e) {
                        $this->showError($e->getMessage(), $data);
                        return;
                    }
                }

                // try to save form data
                try {
                    $model->save();
                    Mage::getSingleton('customer/session')->
                                        addSuccess(Mage::helper('testimonials')->
                                        __('Thank you for your testimonial'));
                    Mage::getSingleton('customer/session')->unsTestimonialsFormData();
                    $this->_redirect('*/*/new');
                    return;
                } catch (Exception $e) {
                    $this->showError($e->getMessage(), $data);
                    return;
                }
            }
            $this->_redirect('*/*/new');
        }

        private function showError($error, $data) 
        {
            Mage::getSingleton('customer/session')->addError($error);
            Mage::getSingleton('customer/session')->setTestimonialsFormData($data);
            $this->_redirect('*/*/new');
        }
	}