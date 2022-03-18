<?php

namespace Omnipay\bitBanker\Message;

use Omnipay\Common\Message\AbstractResponse;

class InvoiceResponse extends AbstractResponse
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
        return $this->data['link'];
    }

    public function getMessage()
    {

        return $this->data;

    }
}