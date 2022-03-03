<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class InvoiceResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {

        return ($this->data['result'] == 'success');

    }

    public function getInvoiceId()
    {
        return $this->data['id'];
    }

    public function getInvoiceLink()
    {
        return $this->data['link'];
    }

    public function getMessage()
    {

        return $this->data;

    }
    
    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->getInvoiceLink();
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }
}
