<?php

namespace Mtools\SpamBlocker\Plugin;

use \Mtools\SpamBlocker\Plugin\AbstractPlugin;
use \Magento\Framework\Controller\Result\Redirect;
use \Magento\Customer\Controller\Account\CreatePost;
use \Closure;

class CreatePostPlugin extends AbstractPlugin
{
    /**
     * @param CreatePost $subject
     * @param Closure    $proceed
     *
     * @return Redirect|mixed
     */
    public function aroundExecute(CreatePost $subject, Closure $proceed)
    {
        $message = __('Plugin.');

        if (!$this->isSpam($this->getStringToValidate())) {
            // call the core observed function
            return $proceed();
        }

        $message = __('There is already an account with this email address. If you are sure that it is your email address.');
        $this->messageManager->addError($message);

        $defaultUrl = $this->urlModel->getUrl('*/*/create', ['_secure' => true]);
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
        $firstname = strtolower(!empty($firstname)?$firstname:'');
        $lastname = strtolower(!empty($lastname)?$lastname:'');

        return $firstname.$lastname;
    }
}
