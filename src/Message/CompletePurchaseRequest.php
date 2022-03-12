<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{

    public $data;
    public $checkString;
    public $requestData;

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

    public function setData($value)
    {
        return $this->setParameter('data',json_decode($value,1));
    }

    public function setSign($value) {
        return $this->setParameter('sign',$value);
    }

    public function getSign() {
        return $this->getParameter('sign');
    }

    public function setSign_2($value) {
        return $this->setParameter('sign2',$value);
    }

    public function getSign2() {
        return $this->getParameter('sign2');
    }

    public function getData()
    {
        return $this->getParameter('data');
    }

    public function getTransactionId()
    {

        $additionalData = $this->getData();

        return $additionalData['transactionId'];
    }

    public function setAmount($value)
    {
        return $this->setParameter('payed_amount',$value);
    }

    public function getAmount()
    {
        return $this->getParameter('payed_amount');
    }
    
    public function getMoney()
    {
        return NULL;
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency',$value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
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
        return $this->setParameter('header',$value);
    }

    public function getHeader()
    {
        return $this->getParameter('header');
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

    public function setRequestData($value) {

        $this->requestData = $value;

        return $this;

    }

    public function prepareSignString(): string
    {

        $return = $this->requestData['currency'];
        $return .= $this->requestData['amount'];
        $return .= $this->requestData['header'];
        $return .= $this->requestData['description'];

        return $return;

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
