<?php
/**
 * Lava driver for Omnipay PHP payment library
 *
 * @link      https://github.com/getviewerspro/omnipay-lava
 * @package   omnipay-lava
 * @license   MIT
 * @copyright Copyright (c) 2023, getViewersPRO (https://getviewers.pro/)
 */

namespace Omnipay\Lava\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Digiseller Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @var CompletePurchaseRequest|RequestInterface
     */
    protected $request;
    protected $customFields;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $data; 
        
        info(['Lava webhook data: ', $this->data]);

        if ($this->getSign() !== $this->calculateSignature()) {
            throw new InvalidResponseException('Invalid hash');
        }
    }

    public function isSuccessful()
    {
        return $this->data['status'] === 'success';
    }

    public function getTransactionId()
    {
        return $this->data['order_id'];
    }

    public function getAmount()
    {
        return (string)$this->data['amount'];
    }  

    public function getCurrency()
    {
        return 'RUB';
    }    
    
    public function getMoney()
    {
        return (string)$this->data['credited']();
    }

    public function getTransactionReference()
    {
        return $this->data['invoice_id'];
    }

    public function getSign()
    {
        info(['Lava webhook Authorization: ', $this->request->header('Authorization')]);
        
        return $this->request->header('Authorization');
    }
    
    public function calculateSignature()
    {
        $signStr = json_encode($this->data['custom_fields'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        
        return hash_hmac(
                'sha256',
                $signStr,
                $this->request->getSecretKeyAdd()
            );
    }
}
