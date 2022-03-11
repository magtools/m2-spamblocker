<?php

namespace Mtools\SpamBlocker\Plugin;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

class CreatePostPlugin
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var \Magento\Framework\UrlInterface */
    protected $urlModel;

    const CONFIGURATION_PATH = "spam_blocker/general/";

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\UrlFactory $urlFactory)
    {
        $this->messageManager = $context->getMessageManager();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->redirect = $context->getRedirect();
        $this->scopeConfig = $scopeConfig;
        $this->urlModel = $urlFactory->create();
    }

    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param \Closure                                        $proceed
     *
     * @return \Magento\Framework\Controller\Result\Redirect|mixed
     */
    public function aroundExecute(\Magento\Customer\Controller\Account\CreatePost $subject, \Closure $proceed)
    {
        $message = __('Plugin.');
        if (!$this->isSpam()) {
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
     * @return bool
     */
    public function isSpam()
    {
        $firstname = $this->request->getParam('firstname', false);
        $lastname = $this->request->getParam('lastname', false);
        $firstname = strtolower($firstname);
        $lastname = strtolower($lastname);

        //Banned utf-8 scripts like Han(Chinese)
        $bannedScripts = $this->getConfig('banned_scripts');
        $bannedScripts = explode(",", $bannedScripts);

        foreach ($bannedScripts as $script) {
            if (!empty($script)) {
                if (preg_match($script, $firstname.$lastname)) {
                    return true;
                }
            }
        }

        //Baned strings in name like http or #
        $bannedList = $this->getConfig('banned_list');
        $bannedList = explode("\n", $bannedList);

        foreach ($bannedList as $bannedString) {
            if (!empty($bannedString)) {
                $bannedString = strtolower($bannedString);
                if (strpos($firstname.$lastname,$bannedString) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $config
     *
     * @return mixed
     */
    protected function getConfig($config)
    {
        return $this->scopeConfig->getValue(self::CONFIGURATION_PATH . $config);
    }

}
