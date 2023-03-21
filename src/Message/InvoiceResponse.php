<?php

namespace Omnipay\Lava\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class InvoiceResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        $result = false;

        if (isset($this->data['result'])) {
            if ($this->data['result'] == 0) {
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

    public function getRedirectMethod()
    {
        return 'GET';
    }
}
