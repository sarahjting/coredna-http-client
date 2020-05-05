<?php

namespace HttpClient;

/**
 * HTTPRequest - holds and manages information pertaining to HTTP requests.
 * 
 * Example usage:
 *      $request = new HttpRequest("get", "https://www.example.com"); 
 *      $request->getUri();
 *      $request->getHeaders();
 *      $request->getContext();
 */
class HttpRequest
{
    /**
     * Method of the request.
     *
     * @var string
     */
    var $method;

    /**
     * URI of the request.
     *
     * @var string
     */

    var $uri;

    /**
     * Body of the request. 
     * This is not used for bodyless requests. 
     *
     * @var array
     */
    var $body;

    /**
     * Headers of the request.
     *
     * @var array
     */
    var $headers;

    /**
     * Constructor.
     *
     * @param  string $method
     * @param  string $uri
     * @param  array $body
     * @param  array $headers
     * @return void
     */
    public function __construct(
        string $method,
        string $uri,
        array $body = [],
        array $headers = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->body = $body;
        $this->headers = $headers;

        if (!isset($this->headers["Content-Type"])) {
            $this->headers["Content-Type"] = "application/json";
        }
    }

    /**
     * Gets URI of the request.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Gets headers of the request, as a string.
     *
     * @return string
     */
    public function getHeaders(): string
    {
        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = "{$key}:{$value}";
        }
        return implode("\r\n", $headers);
    }

    /**
     * Get header body, as a string.
     *
     * @return string
     */
    public function getBody(): string
    {
        return json_encode($this->body);
    }

    /**
     * Creates configuration for a stream context. 
     *
     * @return array
     */
    public function getContext(): array
    {
        return [
            'http' => [
                'method' => strtoupper($this->method),
                'header' => $this->getHeaders(),
                'content' => $this->hasBody() ? $this->getBody() : "",
            ]
        ];
    }

    /**
     * Returns whether the current request has a body. 
     *
     * @return bool
     */
    public function hasBody(): bool
    {
        return static::httpMethodAcceptsBody($this->method);
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
            "options",
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
