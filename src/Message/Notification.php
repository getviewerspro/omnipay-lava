<?php

namespace Omnipay\bitBanker\Message;

use GuzzleHttp\Psr7\Response;
use Omnipay\Common\Helper;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\ParametersTrait;
use Symfony\Component\HttpFoundation\ParameterBag;


class Notification implements NotificationInterface
{

    public $data;
    private $checkString;

    use ParametersTrait {
        setParameter as traitSetParameter;
        getParameter as traitGetParameter;
    }

    public function __construct(array $options = [])
    {
        $this->data = json_decode(file_get_contents('php://input'),1);
        $this->initialize();
    }

    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    public function getParameter($key)
    {
        return $this->traitGetParameter($key);
    }

    public function setParameter($key, $value)
    {
        return $this->traitSetParameter($key, $value);
    }

    public function getData() {

        if (isset($this->data['id'])){
            $this->setParameter('invoiceId',$this->data['id']);
        }

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

    public function setInvoiceRequestData($arFields)
    {
        return $this->setParameter('invoiceRequestData',$arFields);
    }

    public function getInvoiceRequestData()
    {
        return $this->getParameter('invoiceRequestData');
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

    public function check()
    {

        $this->checkString = $this->prepareSignString();

        return ($this->checkSign() && $this->checkSign2());

    }

    protected function prepareSignString()
    {

        $invoiceData = $this->getInvoiceRequestData();

        $return = $invoiceData['currency'];
        $return .= $invoiceData['amount'];
        $return .= $invoiceData['header'];
        $return .= $invoiceData['description'];

        return $return;

    }

    private function checkSign()
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

    private function checkSign2()
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

    public function getTransactionReference()
    {
        return false;
    }

    public function getTransactionStatus()
    {
        if ($this->data['payed']) {
            return NotificationInterface::STATUS_COMPLETED;
        } else {
            return NotificationInterface::STATUS_FAILED;
        }
    }
}