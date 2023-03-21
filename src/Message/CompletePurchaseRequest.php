<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{

    public $data;
    public $checkString;
    public $requestData;

    public function send() {

        return $this;

    }

    public function setInvoiceId($value)
    {
        return $this->setParameter('invoice_id',$value);
    }

    public function getInvoiceId()
    {
        return $this->getParameter('invoice_id');
    }

    public function setData($value)
    {
        return $this->setParameter('custom_fields',$value);
    }

    public function getData()
    {
        return $this->getParameter('custom_fields');
    }

    public function setSign($value) {
        return $this->setParameter('sign',$value);
    }

    public function getSign() {
        return $this->getParameter('sign');
    }

    public function getTransactionId()
    {
        return $this->getParameter('order_id');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount',$value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }
    
    public function getMoney()
    {
        return NULL;
    }

    public function setError($value)
    {
        return $this->setParameter('error',$value);
    }

    public function getError()
    {
        return $this->getParameter('error');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency',$value);
    }

    public function setDescription($value)
    {
        return $this->setParameter('description',$value);
    }

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function check()
    {

        $this->checkString = $this->prepareSignString();

        if (!($this->checkSign() && $this->checkSign2())) {
            throw new InvalidResponseException('Invalid sign');
        }

    }

    public function prepareSignString() {
        return json_encode($this->prepareRequestBody(),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    } 

    private function prepareRequestBody()
    {
        info($this->getSign());

        $this->setHeaders([
            "signature" => $this->getSign()
        ]);

        $data = $this->parameters->all();

        return array_filter([
            'sum'               => $this->getAmount(),
            'orderId'           => $this->getTransactionId(),
            'shopId'            => $this->getShopId(),
            'includeService'    => $this->getPaymentMethods(),
            'comment'           => $this->getDescription(),
        ]);
    }

    public function checkSign()
    {

        $sign = $this->processSign();

        return ($sign === $this->getSign());

    }

    public function processSign()
    {

        return hash_hmac(
            'sha256',
            $this->checkString,
            $this->getApiKey()
        );

    }

    public function checkSign2(): bool
    {

        $sign = $this->processSign2();

        return ($sign === $this->getSign2());

    }

    public function processSign2()
    {

        return hash_hmac(
            'sha256',
            $this->checkString,
            $this->getSecretKey()
        );

    }

    public function getMessage()
    {
        return $this->data;
    }

    public function isSuccessful()
    {
        $this->check();

        if ($this->getError() == null) {
            return $this->getPayed();
        } else {
            return false;
        }
    }

    public function sendData($data)
    {
        return $this;
    }
}
