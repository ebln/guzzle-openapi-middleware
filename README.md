OpenAPI Validation Middleware for Guzzle
========================================

[This middleware only adapts league/openapi-psr7-validator for Guzzle, please see their project for documentation](https://github.com/thephpleague/openapi-psr7-validator#readme)

## Installation

```
composer require ebln/guzzle-openapi-middleware
```

## Usage

```
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;

$builder = new ValidatorBuilder();
// call either setSchemaFactory() or one of the from*() methods optionally add a PSR6 cache
// @see https://github.com/thephpleague/openapi-psr7-validator#readme

$middleware = new Middleware($builder->getRequestValidator(), $builder->getResponseValidator());

// @see https://docs.guzzlephp.org/en/stable/handlers-and-middleware.html#middleware
$stack = HandlerStack::create();
$stack->push($middleware, 'openapi_validation');
$client = new Client(['handler' => $stack]);
```
