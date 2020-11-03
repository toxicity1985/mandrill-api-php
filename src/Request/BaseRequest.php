<?php

namespace Mandrill\Request;

use Mandrill\Mandrill;

class BaseRequest
{
    protected Mandrill $mandrill;

    public function __construct(Mandrill $mandrill)
    {
        $this->mandrill = $mandrill;
    }
}