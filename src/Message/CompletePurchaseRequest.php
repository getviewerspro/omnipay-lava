<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{

    public $data;
    public $checkString;

    /**
     * @throws InvalidResponseException
     */
    public function getData()
    {

        if (isset($this->data['id'])){
            $this->setParameter('invoiceId',$this->data['id']);
        }

        $this->check();

    }

    public function send() {

        $this->data = json_decode(file_get_contents('php://input'),1);

        $this->getData();

        return $this;

    }

    public function setInvoiceId($value)
    {
        return $this->setParameter('invoiceId',$value);
    }

    public function getInvoiceId()
    {
        return $this->getParameter('invoiceId');
    }

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function getAmount()
    {
        return $this->data['payed_amount'];
    }

    public function getCurrency()
    {
        return $this->data['currency'];
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey',$value);
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey',$value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setHeader($value)
    {
        return$this->setParameter('header',$value);
    }

    public function check()
    {

        $this->checkString = $this->prepareSignString();

        if (!($this->checkSign() && $this->checkSign2())) {
            throw new InvalidResponseException('Invalid sign');
        }

    }

    public function prepareSignString(): string
    {

        $return = $this->getParameter('currency');
        $return .= $this->getParameter('amount');
        $return .= $this->getParameter('header');
        $return .= $this->getParameter('description');

        return $return;

    }

    public function checkSign()
    {

        $sign = $this->getSign();

        return ($sign === $this->data['sign']);

    }

    public function getSign()
    {

        return hash_hmac(
            'sha256',
            $this->checkString,
            $this->getApiKey()
        );

    }

    public function checkSign2(): bool
    {

        $sign = $this->getSign2();

        return ($sign === $this->data['sign_2']);

    }

    public function getSign2()
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
        if (!isset($this->data['error'])) {
            return $this->data['payed'];
        } else {
            return false;
        }
    }

    public function sendData($data)
    {
        return $this;
    }
}