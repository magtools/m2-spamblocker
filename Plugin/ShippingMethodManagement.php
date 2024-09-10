<?php

namespace Mtools\SpamBlocker\Plugin;

use \Mtools\SpamBlocker\Plugin\AbstractPlugin;
use \Magento\Checkout\Model\Session;
use \Magento\Quote\Api\CartRepositoryInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\UrlFactory;

class ShippingMethodManagement extends AbstractPlugin
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * ShippingMethodManagement constructor.
     *
     * @param Session                 $session
     * @param ScopeConfigInterface    $scopeConfig
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        UrlFactory $urlFactory,
        CartRepositoryInterface $quoteRepository,
        Session $session
    ) {
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
        $this->quoteRepository = $quoteRepository;

        parent::__construct(
            $context,
            $scopeConfig,
            $urlFactory
        );
    }

    /**
     * @param $shippingMethodManagement
     * @param $output
     *
     * @return array
     */
    public function afterEstimateByAddress($shippingMethodManagement, $output, $cartId)
    {
        return $this->getRates($output);
    }

    /**
     * @param $shippingMethodManagement
     * @param $output
     *
     * @return array
     */
    public function afterEstimateByAddressId($shippingMethodManagement, $output, $cartId)
    {
        return $this->getRates($output);
    }

    /**
     * @param $shippingMethodManagement
     * @param $output
     *
     * @return array
     */
    public function afterEstimateByExtendedAddress($shippingMethodManagement, $output, $cartId)
    {
        return $this->getRates($output);
    }

    /**
     * @return array
     */
    public function getRates($outputRates)
    {
        if ($this->isSpam($this->getStringToValidate())) {
            // $blacklistRates = [];
            return [];
        }

        return $outputRates;
    }

    /**
     * @return string
     */
    protected function getStringToValidate()
    {
        $shipping_info = $this->session->getQuote()->getShippingAddress();
        $firstname = $shipping_info->getFirstname();
        $lastname = $shipping_info->getLastname();
        $telephone = $shipping_info->getTelephone();
        $city = $shipping_info->getCity();
        $address = $shipping_info->getStreet();
        $firstname = strtolower(!empty($firstname)?$firstname:'');
        $lastname = strtolower(!empty($lastname)?$lastname:'');
        $telephone = strtolower(!empty($telephone)?$telephone:'');
        $city = strtolower(!empty($city)?$city:'');
        $address = strtolower(!empty($address[0])?$address[0]:'');

        return $firstname.$lastname.$telephone.$city.$address;
    }
}
