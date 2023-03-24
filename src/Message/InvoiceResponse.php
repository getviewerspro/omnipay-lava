<?php

namespace Omnipay\Lava\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class InvoiceResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $request;

    public function __construct(InvoiceRequest $request, $data)
    {
        $this->request = $request;
        $this->data    = $data; 
        
        info(['Digiseller InvoiceResponse data and locale: ', $this->data, $this->request->getLocale()]);
    }
    
    public function isSuccessful()
    {
        if (!isset($this->data['data']['status'])) {
            return false;
        }
        
        return ($this->data['data']['status'] == 0) ? true : false;

    }

    public function getInvoiceId()
    {
        return $this->data['data']['id'];
    }

    public function getInvoiceLink()
    {
        $url = $this->data['data']['url'] ?? '';
        
        if (!empty($url)) {
            $url = str_replace('=ru', '='.$this->getLocale(), $url);
        }
            
        return $url;
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
}
