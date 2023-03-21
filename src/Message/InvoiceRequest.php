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
        $this->response = new InvoiceResponse($this,$result);
        info(json_encode($this->response));
        return $this->response;
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
        info([$this->getSign(), $this->getShopId(), $this->getTransactionId()]);

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

}
