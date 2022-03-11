<?php

namespace Omnipay\bitBanker;

use Omnipay\bitBanker\Message\CompletePurchaseRequest;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase {

    protected $response;

    public function setUp(): void
    {
        parent::setUp();

        $this->response = new CompletePurchaseRequest($this->getHttpClient(),$this->getHttpRequest());

        $this->response->initialize([
            'apiKey' => '1234',
            'secretKey' => '4321',
            'transactionId' => '321dd',
            'currency' => 'RUB',
            'amount' => 5000,
            'description' => 'buy something special',
            'header' => 'Company name',
        ]);

        $this->response->data = json_decode(file_get_contents(__DIR__ . "\Mock\invoiceWebhook.json"),1);

    }

    public function testResponseSign() {

        $this->response->checkString = $this->response->prepareSignString();

        $this->assertTrue($this->response->checkSign());
        $this->assertTrue($this->response->checkSign2());

    }

    public function testResponseSignException() {

        $this->response->setHeader('wrong header');

        $this->expectException(InvalidResponseException::class);

        $this->response->check();

    }

    public function testIsSuccessfulTrue()
    {
        $this->assertTrue($this->response->isSuccessful());
    }

    public function testGetTransactionId()
    {
        $this->assertEquals('321dd',$this->response->getTransactionId());
    }

    public function testGetAmount()
    {
        $this->assertEquals(5000,$this->response->getAmount());
    }

    public function testGetCurrency()
    {
        $this->assertEquals("RUB",$this->response->getCurrency());
    }

}