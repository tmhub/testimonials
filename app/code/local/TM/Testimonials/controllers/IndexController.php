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
                ->setIsAjax(true)
                ->toHtml();
            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(array('outputHtml' => $output))
            );
        } else {
            $this->getLayout()->getBlock('head')->setTitle(
                Mage::helper('testimonials')->__('Testimonials')
            );
            //apply custom layout from config
            $this->getLayout()->getBlock('root')
                 ->helper('page/layout')
                 ->applyTemplate(Mage::helper('testimonials')->getListLayout());
            $this->renderLayout();
        }
    }

    public function newAction()
    {
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        if (!Mage::helper('testimonials')->allowGuestSubmit() &&
            !Mage::getSingleton('customer/session')->isLoggedIn())
        {
            Mage::getSingleton('customer/session')->addError(
                Mage::helper('testimonials')->__('Please log in to submit testimonial.')
            );
            $this->getResponse()->setRedirect(Mage::getUrl('customer/account'));
        }

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle(
            Mage::helper('testimonials')->__('New Testimonial')
        );
        //apply custom layout from config
        $this->getLayout()->getBlock('root')
             ->helper('page/layout')
             ->applyTemplate(Mage::helper('testimonials')->getFormLayout());
        $this->renderLayout();
    }

    public function postAction()
    {
        $this->_initLayoutMessages('customer/session');
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            if (!($data['name'] && $data['email'] && $data['message'])) {
                Mage::getSingleton('customer/session')->addError(
                    Mage::helper('testimonials')->__('Please, fill all required fields.')
                );
                Mage::getSingleton('customer/session')->setTestimonialsFormData($data);
                $this->_redirectReferer();
                return;
            }

            $model = Mage::getModel('tm_testimonials/data');

            $model->setStoreId(Mage::app()->getStore()->getStoreId());
            $model->setName($data['name']);
            $model->setEmail($data['email']);
            $model->setMessage($data['message']);
            $model->setCompany(isset($data['company']) ? $data['company'] : '');
            $model->setWebsite(isset($data['website']) ? $data['website'] : '');
            $model->setTwitter(isset($data['twitter']) ? $data['twitter'] : '');
            $model->setFacebook(isset($data['facebook']) ? $data['facebook'] : '');
            if (isset($data['rating'])) $model->setRating($data['rating']);
            if (Mage::helper('testimonials')->isAutoApprove())
                $model->setStatus(TM_Testimonials_Model_Data::STATUS_ENABLED);

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
                    Mage::getSingleton('customer/session')->addError($e->getMessage());
                    Mage::getSingleton('customer/session')->setTestimonialsFormData($data);
                    $this->_redirectReferer();
                    return;
                }
            }

            // try to save form data
            try {
                $model->save();
                Mage::getSingleton('customer/session')->
                                    addSuccess(Mage::helper('testimonials')->
                                    getSentMessage());
                Mage::getSingleton('customer/session')->unsTestimonialsFormData();

                // send email notification to admin
                try {
                    $data['status'] = $model->getStatus();
                    $data['image'] = $model->getImage();
                    Mage::dispatchEvent('testimonials_notify_admin_testimonial_submit',
                        array( 'testimonial'  => $data )
                    );
                } catch (Mage_Core_Exception $e) {
                    Mage::getSingleton('customer/session')->addError(
                        $e->getMessage()
                    );
                }
                // clear testimonials list block cache after new item was added
                Mage::app()->cleanCache(array('tm_testimonials_list'));
            } catch (Exception $e) {
                Mage::getSingleton('customer/session')->addError($e->getMessage());
                Mage::getSingleton('customer/session')->setTestimonialsFormData($data);
                $this->_redirectReferer();
                return;
            }
        }
        $this->_redirectReferer();
    }
}
