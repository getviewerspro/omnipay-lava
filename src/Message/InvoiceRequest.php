<?php

namespace Omnipay\Lava\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class InvoiceRequest extends AbstractRequest
{
    protected $method     = 'POST';
    public $productionUri = "https://api.lava.ru/business/invoice/create";

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'shopId',
            'secretKey',
            'amount',
            'transactionId'
        );

        return $this->prepareSign()->getRequestBody();
    }

    public function sendData($result)
    {
        return new InvoiceResponse($this, $result);
    }

    private function getRequestBody()
    {
        
        $return =  array_filter([
            'sum'               => $this->getAmount(),
            'orderId'           => $this->getTransactionId(),
            'shopId'            => $this->getShopId(),
            'includeService'    => (!empty($this->getPaymentMethod()) ? [$this->getPaymentMethod()] : $this->getPaymentMethods()),
            'comment'           => $this->getDescription(),
        ]);
        
        dd($return, $this->getPaymentMethod(), $this->getPaymentMethods());
        
        $return['customFields'] = json_encode($return);
        
        return $return;
    }

    public function prepareSign() 
    {
        $signStr = json_encode($this->getRequestBody(), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        
        return $this->setSign(
            hash_hmac(
                'sha256',
                $signStr,
                $this->getSecretKey()
            )
        );
    }
    
    public function send()
    {
        $data = $this->getData();
        
        $response = $this->getClient($data);
        $result = json_decode($response, 1);
        
        return $this->sendData($result);
    }
    
    protected function getClient($data)
    {        
        $data = json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $sign = $this->getSign();

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->getEndpoint(), 
            CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true, 
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
            CURLOPT_CUSTOMREQUEST => 'POST', 
            CURLOPT_POSTFIELDS => $data, 
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json', 'Content-Type: application/json', 'Signature: ' . $sign
            ), 
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return $response;
    }

}
