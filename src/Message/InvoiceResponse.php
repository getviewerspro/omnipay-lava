<?php

namespace Omnipay\Lava\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class InvoiceResponse extends AbstractResponse implements RedirectResponseInterface
{

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
