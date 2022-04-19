<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class InvoiceRequest extends AbstractRequest
{
    protected $method     = 'POST';
    public $productionUri = "https://api.aws.bitbanker.org/latest/api/v1/invoices";
    public $testUri       = "https://api.dev.aws.bitbanker.org/latest/api/v1/invoices";

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'payment_currencies',
            'currency',
            'amount',
            'header',
            'is_convert_payments',
            'apiKey',
            'transactionId'
        );

        $this->sign()->prepareRequest();

        return $this->prepareRequestBody();
    }

    public function setPaymentCurrencies($value)
    {
        return $this->setParameter('payment_currencies',$value);
    }

    public function getPaymentCurrencies()
    {
        return $this->getParameter('payment_currencies');
    }

    public function setIsConvertPayments($value)
    {
        return $this->setParameter('is_convert_payments',$value);
    }

    public function getIsConvertPayments()
    {
        return $this->getParameter('is_convert_payments');
    }

    public function setHeader($value)
    {
        return $this->setParameter('header',$value);
    }

    public function getHeader()
    {
        return $this->getParameter("header");
    }

    public function sendData($result)
    {
        return $this->response = new InvoiceResponse($this,$result);
    }

    public function sign() {

        return $this->setSign(
            hash_hmac(
                'sha256',
                $this->prepareSignString(),
                $this->getApiKey()
            )
        );

    }

    public function prepareSignString() {

        $return = $this->getParameter('currency');
        $return .= $this->getParameter('amount');
        $return .= $this->getParameter('header');
        $return .= $this->getParameter('description');

        return $return;
    }

    private function setSign($value) {

        return $this->setParameter('sign',$value);

    }

    public function getSign() {

        return $this->getParameter('sign');

    }

    private function prepareRequest()
    {
        $this->setHeaders([
            "X-API-KEY" => $this->getApiKey()
        ]);

        return $this;
    }

    private function prepareRequestBody()
    {
        $data = $this->parameters->all();

        return [
            'payment_currencies' => $data['payment_currencies'],
            'currency' => $data['currency'],
            'amount' => $data['amount'],
            'header' => $data['header'],
            'is_convert_payments' => $data['is_convert_payments'],
            'description' => $data['description'],
            'sign' => $data['sign'],
            'data' => (object) [
                'transactionId' => $data['transactionId'],
                'header' => $data['header'],
                'description' => $data['description']
            ]
        ];
    }

}
