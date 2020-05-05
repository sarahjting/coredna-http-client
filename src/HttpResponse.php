<?php

namespace HttpClient;

use HttpClient\Exceptions\InvalidResponseException;
use HttpClient\Exceptions\InvalidRequestException;
use HttpClient\Exceptions\RemoteServerErrorException;

/**
 * HttpResponse - holds and manages information pertaining to HTTP responses.
 * 
 * Example usage:
 *      $client = new HttpClient();
 *      $response = $client->get("https://www.example.com/"); 
 *      echo $response->getStatusCode();
 *      echo $response->getBody();
 *      var_dump($response->getHeaders());
 */
class HttpResponse
{
    /**
     * Response body. Where the response is JSON formatted, this is an array.
     * Otherwise, it is a string.
     *
     * @var string|array
     */
    var $body;

    /**
     * Response headers.
     *
     * @var array
     */
    var $headers = [];

    /**
     * Response's status code.
     *
     * @var int
     */
    var $statusCode;

    /**
     * Constructor.
     *
     * @param  array $headers
     * @param  string $body
     * @return void
     */
    public function __construct(array $headers, string $body)
    {
        $this->parseHeaders($headers);
        $this->parseBody($body);
    }

    /**
     * Processes and populates object with an array of headers.
     *
     * @param  array $headers
     * @return void
     */
    private function parseHeaders(array $headers): void
    {
        if (!count($headers)) {
            throw new InvalidResponseException();
        }

        $this->statusCode = explode(" ", $headers[0])[1];
        if ($this->statusCode >= 400 && $this->statusCode < 500) {
            throw new InvalidRequestException();
        }
        if ($this->statusCode >= 500 && $this->statusCode < 600) {
            throw new RemoteServerErrorException();
        }

        foreach (array_slice($headers, 1) as $header) {
            $header = explode(":", $header, 2);
            $this->headers[$header[0]] = trim($header[1]);
        }
    }

    /**
     * Processes and populates object with the response body. 
     * If JSON is expected, JSON will be parsed.
     *
     * @param  string $body
     * @return void
     */
    private function parseBody(string $body): void
    {
        if ($this->isJson()) {
            $this->body = (array) json_decode($body);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidResponseException();
            }
        } else {
            $this->body = $body;
        }
    }

    /**
     * Returns body of the response. 
     * In the case of JSON responses, this will return an associative array.
     * Otherwise it will return a string.
     *
     * @return string|array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Gets headers as associative array.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Returns status code as integer.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    /**
     * Returns whether the current response is json content.
     *
     * @return bool
     */
    public function isJson(): bool
    {
        return isset($this->headers["Content-Type"])
            && substr($this->headers["Content-Type"], 0, 16) === "application/json";
    }
}
