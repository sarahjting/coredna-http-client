<?php

namespace HttpClient;

use HttpClient\HttpResponse;

class HttpRequest
{
    var $method;
    var $uri;
    var $query;
    var $options;

    /**
     * Constructor.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $body
     * @param  array $options
     * @return void
     */
    public function __construct(string $method, string $uri, array $body = [], array $options = [])
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->body = $body ? json_encode($body) : "";
    }

    /**
     * Sends the request.
     *
     * @return HttpResponse
     */
    public function send(): HttpResponse
    {
        $result = @file_get_contents($this->uri, false, $this->createContext());
        return new HttpResponse($http_response_header ?? [], $result);
    }

    /**
     * Creates a stream context for the request. 
     *
     * @return resource
     */
    public function createContext()
    {
        $context = stream_context_create([
            'http' => [
                'method' => strtoupper($this->method),
                'header' => 'Content-Type: application/json',
                'content' => $this->body,
            ]
        ]);
        return $context;
    }

    /**
     * Returns an array of all methods which can be accepted.
     *
     * @return array
     */
    public static function acceptableHttpMethods(): array
    {
        return [
            "get",
            "post",
            "put",
            "patch",
            "delete",
            "option",
            "head"
        ];
    }

    /**
     * Returns an array of all methods that can format a request body.
     *
     * @return array
     */
    public static function httpMethodsWithBody(): array
    {
        return [
            "post",
            "put",
            "patch",
        ];
    }

    /**
     * Checks whether the provided HTTP method is valid.
     *
     * @param  string $inMethod
     * @return boolean
     */
    public static function isAcceptableHttpMethod(string $inMethod): bool
    {
        return in_array($inMethod, static::acceptableHttpMethods());
    }

    /**
     * Checks whether the provided HTTP method can format a body.
     *
     * @param  string $inMethod
     * @return boolean
     */
    public static function httpMethodAcceptsBody(string $inMethod): bool
    {
        return in_array($inMethod, static::httpMethodsWithBody());
    }
}
