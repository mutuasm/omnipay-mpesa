<?php

namespace Omnipay\Mpesa;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;

/**
 * Mpesa Gateway api
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = [])
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class Gateway extends AbstractGateway
{

    /**
     * Version of our gateway.
     */
    const GATEWAY_VERSION = "1.0";

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Mpesa';
    }

    /**
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return array(
            'shortcode' => '',
            'consumer_key' => '',
            'consumer_secret' => '',
            'token' => '',
            'testMode' => false,
        );
    }

    /**
     * @return string
     */
    public function getShortCode(): string
    {
        return $this->getParameter('shortcode');
    }

    /**
     * @return Gateway
     */
    public function setShortCode($value): Gateway
    {
        return $this->setParameter('shortcode', $value);
    }

    public function getConsumerKey(): Gateway
    {
        return $this->getParameter('consumer_key');
    }

    public function setConsumerKey($value): Gateway
    {
        return $this->setParameter('consumer_key', $value);
    }

    public function getConsumerSecret(): Gateway
    {
        return $this->getParameter('consumer_secret');
    }

    public function setConsumerSecret($value): Gateway
    {
        return $this->setParameter('consumer_secret', $value);
    }

    public function getPassKey(): Gateway
    {
        return $this->getParameter('pass_key');
    }

    public function setPassKey($value): Gateway
    {
        return $this->setParameter('pass_key', $value);
    }

     /**
     * Get OAuth 2.0 access token.
     *
     * @param bool $createIfNeeded [optional] - If there is not an active token present, should we create one?
     * @return string
     */
    public function getToken($createIfNeeded = true): string
    {
        if ($createIfNeeded && !$this->hasToken()) {
            $response = $this->createToken()->send();
            if ($response->isSuccessful()) {
                $data = $response->getData();
                if (isset($data['access_token'])) {
                    $this->setToken($data['access_token']);
                    $this->setTokenExpires(time() + $data['expires_in']);
                }
            }
        }

        return $this->getParameter('token');
    }

    /**
     * Create OAuth 2.0 access token request.
     *
     * @return \Omnipay\Mpesa\Message\MpesaTokenRequest
     */
    public function createToken()
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\MpesaTokenRequest', array());
    }

    /**
     * Set OAuth 2.0 access token.
     *
     * @param string $value
     * @return MpesaGateway provides a fluent interface
     */
    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    /**
     * Get OAuth 2.0 access token expiry time.
     *
     * @return integer
     */
    public function getTokenExpires()
    {
        return $this->getParameter('tokenExpires');
    }

    /**
     * Set OAuth 2.0 access token expiry time.
     *
     * @param integer $value
     * @return MpesaGateway provides a fluent interface
     */
    public function setTokenExpires($value)
    {
        return $this->setParameter('tokenExpires', $value);
    }

    /**
     * Is there a bearer token and is it still valid?
     *
     * @return bool
     */
    public function hasToken()
    {
        $token = $this->getParameter('token');

        $expires = $this->getTokenExpires();
        if (!empty($expires) && !is_numeric($expires)) {
            $expires = strtotime($expires);
        }

        return !empty($token) && time() < $expires;
    }

    /**
     * Create Request
     *
     * This overrides the parent createRequest function ensuring that the OAuth
     * 2.0 access token is passed along with the request data -- unless the
     * request is a MpesaTokenRequest in which case no token is needed.  If no
     * token is available then a new one is created (e.g. if there has been no
     * token request or the current token has expired).
     *
     * @param string $class
     * @param array $parameters
     */
    public function createRequest($class, array $parameters = array())
    {
        if (!$this->hasToken() && $class !== '\Omnipay\Mpesa\Message\MpesaTokenRequest') {
            // This will set the internal token parameter which the parent
            // createRequest will find when it calls getParameters().
            $this->getToken(true);
        }

        return parent::createRequest($class, $parameters);
    }

    /**
     * Create a purchase request.
     *
     * PayPal provides various payment related operations using the /payment
     * resource and related sub-resources.
     *
     * @link https://developer.safaricom.co.ke/get-started
     * @param array $parameters
     * @return \Omnipay\Mpesa\Message\MpesaPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mpesa\Message\MpesaPurchaseRequest', $parameters);
    }

public function __call(string $name,array $arguments)
{
    // TODO: Implement @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
    // TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
}
}
