<?php

namespace Omnipay\bitBanker;

use Omnipay\bitBanker\Message\Notification;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected $gateway;

    private $options;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(),$this->getHttpRequest());

        $this->options = [
            "payment_currencies" => "BTC",
            "currency" => "RUB",
            "amount" => '5000',
            "description" => "buy something special",
            "header" => "Company name",
            "is_convert_payments" => false,
            "testMode" => true
        ];
    }

    public function testPreparedStringDataForSign()
    {

        $preparedString = "RUB5000Company namebuy something special";

        $request = $this->gateway->createInvoice($this->options);

        $this->assertEquals($preparedString,$request->prepareSignString());

    }

    public function testSignCreate()
    {
        $sign = $this->prepareTestSignWithApiKey();

        $this->gateway->setApiKey("1234");

        $request = $this->gateway->createInvoice($this->options)->sign();

        $this->assertEquals($sign,$request->getSign());

    }

    public function testRequestBody()
    {

        $data = $this->options;
        unset($data['testMode']);
        $data['sign'] = $this->prepareTestSignWithApiKey();

        $this->gateway->setApiKey("1234");

        $request = $this->gateway->createInvoice($this->options);

        $this->assertEquals($data,$request->getData());

    }

    public function testSuccessSendRequest(){

        $this->setMockHttpResponse('invoiceCreateResponse.txt');

        $this->gateway->setApiKey("1234");

        $request = $this->gateway->createInvoice($this->options);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals("123456qwerty",$response->getInvoiceId());
        $this->assertEquals("https://app.bitbanker.ru/external/invoice/123456qwerty",$response->getInvoiceLink());
    }

    public function testSuccessResponseData()
    {
        $expectedData = [
            'result' => 'success',
            'id' => '123456qwerty',
            'link' => 'https://app.bitbanker.ru/external/invoice/123456qwerty',
        ];

        $this->setMockHttpResponse('invoiceCreateResponse.txt');

        $this->gateway->setApiKey("1234");

        $request = $this->gateway->createInvoice($this->options);

        $response = $request->send();

        $this->assertEquals($expectedData,$response->getMessage());
    }

    public function testIsTestMode()
    {

        $request = $this->gateway->createInvoice($this->options);

        $this->assertTrue($request->getTestMode());

    }

    private function prepareTestSignWithApiKey() {
        return hash_hmac(
            'sha256',
            "RUB5000Company namebuy something special",
            "1234"
        );
    }

    private function prepareTestSignWithSecretKey() {
        return hash_hmac(
            'sha256',
            "RUB5000Company namebuy something special",
            "4321"
        );
    }

}