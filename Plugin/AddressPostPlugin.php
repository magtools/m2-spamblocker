<?php

namespace Mtools\SpamBlocker\Plugin;

use \Mtools\SpamBlocker\Plugin\AbstractPlugin;
use \Magento\Framework\Controller\Result\Redirect;
use \Magento\Customer\Controller\Address\FormPost;
use \Closure;

class AddressPostPlugin extends AbstractPlugin
{
    /**
     * @param FormPost $subject
     * @param Closure  $proceed
     *
     * @return Redirect|mixed
     */
    public function aroundExecute(FormPost $subject, Closure $proceed)
    {
        $message = __('Plugin.');

        if (!$this->isSpam($this->getStringToValidate())) {
            // call the core observed function
            return $proceed();
        }

        $message = __('Something went wrong. Please, try again later');
        $this->messageManager->addError($message);

        $defaultUrl = $this->urlModel->getUrl('*/*/*', ['_secure' => true]);
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->redirect->error($defaultUrl));
        return $resultRedirect;
    }

    /**
     * @return string
     */
    protected function getStringToValidate()
    {
        $firstname = $this->request->getParam('firstname', false);
        $lastname = $this->request->getParam('lastname', false);
        $telephone = $this->request->getParam('telephone', false);
        $city = $this->request->getParam('city', false);
        $address = $this->request->getParam('street', false);
        $firstname = strtolower(!empty($firstname)?$firstname:'');
        $lastname = strtolower(!empty($lastname)?$lastname:'');
        $telephone = strtolower(!empty($telephone)?$telephone:'');
        $city = strtolower(!empty($city)?$city:'');
        $address = strtolower(!empty($address[0])?$address[0]:'');

        return $firstname.$lastname.$telephone.$city.$address;
    }
}
