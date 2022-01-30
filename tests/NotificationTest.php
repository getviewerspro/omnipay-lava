<?php

namespace Omnipay\bitBanker;

use Omnipay\bitBanker\Message\Notification;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Tests\TestCase;

class NotificationTest extends TestCase
{

    protected $notification;

    public function setUp(): void
    {
        parent::setUp();

        $this->notification = new Notification();

        $this->notification->initialize(['apiKey' => '1234','secretKey' => '4321']);

        $this->notification->httpResponse = $this->getMockHttpResponse('invoiceWebhook.txt');

        $this->notification->getData();
    }

    public function testAcceptWebhookSignsParse()
    {

        $this->assertEquals('123Ddd',$this->notification->getInvoiceId());

        $this->notification->setInvoiceRequestData([
            "currency" => "RUB",
            "amount" => 5000,
            "description" => "buy something special",
            "header" => "Company name",
        ]);

        $this->assertTrue($this->notification->check());
    }

    public function testAcceptMessageData()
    {

        $expectedMessage = [
            "payed" => true,
            "id" => '123Ddd',
            "amount" => 5000,
            "currency" => "RUB",
            "payed_amount" => 5000,
            "transactions" => [
                [
                    "tx_id" => '1213213123',
                    "amount" => 0.00015,
                    "fee" => 0.0000000025,
                    "currency" => 'BTC'
                ]
            ],
            "data" => [],
            "sign" => "c3987f9054d09f590f00a93d3f388f62c671f6109cf9f85df4eff329bae6c818",
            "sign_2" => "36a78bb6d755027672500fbf5850e38232f2e9fe948292d92ceb55db5ddcba91"
        ];

        $this->assertEquals($expectedMessage,$this->notification->getMessage());


    }

    public function testTransactionReference()
    {
        $this->assertFalse($this->notification->getTransactionReference());
    }

    public function testMessageHasSuccessfulStatus()
    {
        $this->assertEquals(NotificationInterface::STATUS_COMPLETED,$this->notification->getTransactionStatus());
    }

}