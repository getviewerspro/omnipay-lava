<?php

namespace Omnipay\Lava;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public function getName()
    {
        return "Lava";
    }

    public function getDefaultParameters()
    {
        return [
            'locale' => 'en'
        ];
    }

    public function setSecretKey($value)
    {
        return $this->setParameter("secretKey", $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter("secretKey");
    }

    public function setSecretKeyAdd($value)
    {
        return $this->setParameter("secretKeyAdd", $value);
    }

    public function getSecretKeyAdd()
    {
        return $this->getParameter("secretKeyAdd");
    }
    
    public function setPaymentMethods($value)
    {
        return $this->setParameter("paymentMethods", $value);
    }

    public function getPaymentMethods() {
        return $this->getParameter('paymentMethods');
    }
    
    public function setShopId($value)
    {
        return $this->setParameter("shopId", $value);
    }

    public function getShopId() {
        return $this->getParameter('shopId');
    }
    
    public function setLocale($value)
    {
        return $this->setParameter("locale", $value);
    }

    public function getLocale() {
        return $this->getParameter('locale');
    }

    public function createInvoice(array $options = [])
    {
        return $this->createRequest(InvoiceRequest::class, $options);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Lava\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Lava\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Lava\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Lava\Message\CompletePurchaseRequest', $parameters);
    }

}
