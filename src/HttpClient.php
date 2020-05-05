<?php

namespace HttpClient;

use HttpClient\Exceptions\InvalidHttpMethodException;
use HttpClient\Exceptions\InvalidResponseException;
use HttpClient\HttpRequest;
use HttpClient\HttpResponse;

class HttpClient
{
    public function __call(string $method, array $params)
    {
        if (!HttpRequest::isAcceptableHttpMethod($method)) {
            throw new InvalidHttpMethodException();
        }
        if (HttpRequest::httpMethodAcceptsBody($method)) {
            return $this->request($method, $params[0], $params[1] ?? [], $params[2] ?? []);
        } else {
            return $this->request($method, $params[0], [], $params[1] ?? []);
        }
    }

    public function request(string $method, string $uri, array $body = [], array $options = []): HttpResponse
    {
        $request = new HttpRequest($method, $uri, $body, $options);
        $result = $request->send();
        return $result;
    }
}
