<?php

namespace Omnipay\bitBanker;

use Omnipay\bitBanker\Message\Notification;
use Omnipay\Common\AbstractGateway;
use Omnipay\bitBanker\Message\InvoiceRequest;


class Gateway extends AbstractGateway
{

    public function getName()
    {
        return "bitBanker";
    }

    public function getDefaultParameters()
    {
        return [
            "testMode" => false,
        ];
    }

    public function setApiKey($value)
    {
        return $this->setParameter("apiKey",$value);
    }

    public function getApiKey()
    {
        return $this->getParameter("apiKey");
    }

    public function setSecretKey($value)
    {
        return $this->setParameter("secretKey",$value);
    }

    public function getSecretKey()
    {
        return $this->getParameter("secretKey");
    }

    public function createInvoice(array $options = [])
    {
        return $this->createRequest(InvoiceRequest::class, $options);
    }

    public function acceptNotification(array $options = array()): \Omnipay\Common\Message\NotificationInterface
    {
        $obj = new Notification();

        $obj->initialize(array_replace($this->getParameters(),$options));

        return $obj->getData();
    }

}