<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Message\AbstractResponse;

class InvoiceResponse extends AbstractResponse
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
}