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
        [$headers, $result] = $this->executeRequest($request->getUri(), $request->getContext());
        return new HttpResponse($headers, $result);
    }

    /**
     * Executes a request.
     *
     * @return array
     */
    public function executeRequest($uri, $contextOptions): array
    {
        $result = @file_get_contents($uri, false, stream_context_create($contextOptions));
        return [$http_response_header ?? [], $result];
    }
}
