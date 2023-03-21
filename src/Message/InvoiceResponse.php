<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class InvoiceResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {

        $result = false;

        if (isset($this->data['result'])) {
            if ($this->data['result'] == 'success') {
                $result = true;
            }
        }

        return $result;

    }

    public function getInvoiceId()
    {
        return $this->data['id'];
    }

    public function getInvoiceLink()
    {
        return $this->data['url'];
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
