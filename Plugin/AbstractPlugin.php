<?php

namespace Mtools\SpamBlocker\Plugin;

use \Magento\Framework\App\RequestInterface;
use \Magento\Framework\App\ResponseInterface;
use \Magento\Framework\Message\ManagerInterface;
use \Magento\Framework\App\Response\RedirectInterface;
use \Magento\Framework\Controller\Result\RedirectFactory;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\UrlInterface;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\UrlFactory;

class AbstractPlugin
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var UrlInterface
     */
    protected $urlModel;

    protected const CONFIGURATION_PATH = "spam_blocker/general/";

    /**
     * AddressPostPlugin constructor.
     *
     * @param Context              $context
     * @param ScopeConfigInterface $scopeConfig
     * @param UrlFactory           $urlFactory
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        UrlFactory $urlFactory
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->redirect = $context->getRedirect();
        $this->scopeConfig = $scopeConfig;
        $this->urlModel = $urlFactory->create();
    }

    /**
     * @return bool
     */
    public function isSpam($toValidateString)
    {
        //Banned utf-8 scripts like Han(Chinese)
        $bannedScripts = $this->getConfig('banned_scripts');
        $bannedScripts = explode(",", $bannedScripts);

        foreach ($bannedScripts as $script) {
            if (!empty($script)) {
                if (preg_match($script, $toValidateString)) {
                    return true;
                }
            }
        }

        //Baned strings in name like http or #
        $bannedList = $this->getConfig('banned_list');
        $bannedList = explode("\n", $bannedList);

        foreach ($bannedList as $bannedString) {
            if (!empty($bannedString)) {
                //$bannedString = strtolower($bannedString); fix for carriage return
                $bannedString = strtolower(str_replace("\r", '',$bannedString));
                if (strpos($toValidateString, $bannedString) !== false) {
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
