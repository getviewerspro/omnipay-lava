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
    }
    
    public function isSuccessful()
    {
        if (!isset($this->data['data']['status'])) {
            return false;
        }
        
        return ($this->data['data']['status'] == 1) ? true : false;

    }

    public function getInvoiceId()
    {
        return $this->data['data']['id'];
    }

    public function getInvoiceLink()
    {            
        return $this->data['data']['url'] ?? '';
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
        return [
            'lang' => $this->request->getLocale()
        ];
    }
}
