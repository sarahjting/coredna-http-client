<?php

namespace HttpClient;

class HttpResponse
{
    var $body;

    public function __construct($headers, $payload)
    {
        $this->body = $payload;
    }

    public function getBody()
    {
        return $this->body;
    }
}
