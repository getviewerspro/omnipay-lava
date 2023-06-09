<?php

namespace Omnipay\Lava\Message;

use Omnipay\Common\Message\AbstractRequest as Request;

abstract class AbstractRequest extends Request
{
    protected $method = "";
    protected $productionUri = "";

    public function getEndpoint()
    {
        return $this->productionUri;
    }

    public function setHeaders($value) {
        return $this->setParameter('headers', $value);
    }

    public function getHeaders() {
        return $this->getParameter('headers');
    }

    public function setSign($value) {
        return $this->setParameter('sign', $value);
    }

    public function getSign() {
        return $this->getParameter('sign');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter("secretKey",$value);
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
    
    public function setShopId($value)
    {
        return $this->setParameter("shopId", $value);
    }

    public function getShopId() {
        return $this->getParameter('shopId');
    }   
    
    public function setPaymentMethods($value)
    {
        return $this->setParameter("paymentMethods", $value);
    }

    public function getPaymentMethods() {
        return $this->getParameter('paymentMethods');
    }
        
    public function setLocale($value)
    {
        return $this->setParameter("locale", $value);
    }

    public function getLocale() {
        return $this->getParameter('locale');
    }

}
