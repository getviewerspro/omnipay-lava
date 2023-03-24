<?php

namespace Omnipay\Lava\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class InvoiceResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data    = $this->request->getData(); 
        
        info(['Digiseller InvoiceResponse locale: ', $this->request->getLocale()]);
    }
    
    public function isSuccessful()
    {
        if (!isset($this->data['status'])) {
            return false;
        }
        
        return ($this->data['status'] == 0) ? true : false;

    }

    public function getInvoiceId()
    {
        return $this->data['id'];
    }

    public function getInvoiceLink()
    {
        $url = $this->data['url'] ?? '';
        
        if (!empty($url)) {
            $url = str_replace('=ru', '='.$this->getLocale(), $url);
        }
            
        return $this->data['url'] ?? '';
    }

    public function getMessage()
    {
        return $this->data;
    }
    
    public function isRedirect()
    {
        return $this->isSuccessful();
    }

    public function getRedirectUrl()
    {
        return $this->getInvoiceLink();
    }

    public function getRedirectData()
    {
        return [];
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }
}
