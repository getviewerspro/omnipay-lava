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
            'amount',
            'transactionId'
        );

        return $this->sign()->prepareRequestBody();
    }

    public function sendData($result)
    {
        return new InvoiceResponse($this,$result);
    }

    public function sign() {

        return $this->setSign(
            hash_hmac(
                'sha256',
                $this->prepareSignString(),
                $this->getSecretKey()
            )
        );

    }

    public function prepareSignString() {
        return json_encode($this->prepareRequestBody(),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    } 

    private function prepareRequestBody()
    {
        $this->setHeaders([
            "signature" => $this->getSign()
        ]);

        $data = $this->parameters->all();

        $return =  array_filter([
            'sum'               => $this->getAmount(),
            'orderId'           => $this->getTransactionId(),
            'shopId'            => $this->getShopId(),
            'includeService'    => $this->getPaymentMethods(),
            'comment'           => $this->getDescription(),
        ]);
        
        info([$return, $this->getSign()]);
        
        return $return;
    }

}
