# coredna-http-client

HTTP client for Core DNA coding assessment.

## Usage

Will accept the following methods:

| Method                             | Arguments                                                                                                                                                                               |
| ---------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `get`, `delete`, `options`, `head` | <ul><li>**uri:** (string) URI to send request to.</li><li>**headers:**</li> (array) Associative array of headers.</ul>                                                                  |
| `post`, `put`, `patch`             | <ul><li>**uri:** (string) URI to send request to.</li><li>**body:** (array) Associative array of the payload body.</li><li>**headers:**</li> (array) Associative array of headers.</ul> |

Example of client usage:

```
use HttpClient\HttpClient;
$client = new HttpClient();

$response = $client->get("https://www.example.com/");
$response = $client->post("https://www.example.com/", [
        "foo" => "bar",
    ], [
        "Accept" => "application/xml",
    ]);
```

Example of interaction with response objects:

```
$response = $client->get("https://www.example.com/");
$response->getStatusCode(); // integer
$response->getHeaders(); // array
$response->getBody(); // string (array if content type is json)
$response->isJson(); // boolean
```

## Tests

```
phpunit
```
