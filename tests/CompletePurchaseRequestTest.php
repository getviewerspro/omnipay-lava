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
            'payed' => true,
            'id' => '123dd',
            'amount' => 5000,
            'currency' => 'RUB',
            'payed_amount' => 5000,
            'transactions' => [
                [
                    'tx_id' => 1213213123,
                    'amount' => 0.00015,
                    'fee' => 2.5e-9,
                    'currency' => 'BTC'
                ]
            ],
            'data' => [
                "transactionId" => "321dd",
                'currency' => 'RUB',
                'amount' => 5000,
                'description' => 'buy something special',
                'header' => 'Company name',
            ],
            'sign' => 'c3987f9054d09f590f00a93d3f388f62c671f6109cf9f85df4eff329bae6c818',
            'sign_2' => '36a78bb6d755027672500fbf5850e38232f2e9fe948292d92ceb55db5ddcba91'
        ]);

    }

    public function testResponseSign() {

        $this->response->checkString = $this->response->prepareSignString();

        $this->assertTrue($this->response->checkSign());
        $this->assertTrue($this->response->checkSign2());

    }

    public function testResponseSignException() {

        $this->response->setData([
            'transactionId' => "321dd",
            'currency' => 'RUB',
            'amount' => 5000,
            'description' => 'buy something special',
            'header' => 'wrong header',
        ]);

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