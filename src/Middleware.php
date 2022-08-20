<?php

declare(strict_types=1);

namespace Ebln\Guzzle\OpenApi;

use GuzzleHttp\Promise\PromiseInterface;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\RequestValidator;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Middleware
{
    private RequestValidator  $requestValidator;
    private ResponseValidator $responseValidator;

    public function __construct(RequestValidator $requestValidator, ResponseValidator $responseValidator)
    {
        $this->requestValidator  = $requestValidator;
        $this->responseValidator = $responseValidator;
    }

    /**
     * @param callable(RequestInterface, array<string, mixed>): PromiseInterface $handler
     *
     * @return callable(\Psr\Http\Message\RequestInterface, array<string, mixed>): \GuzzleHttp\Promise\PromiseInterface
     */
    public function __invoke(callable $handler): callable
    {
        $responseValidator = $this->responseValidator;

        return function (RequestInterface $request, array $options) use ($handler, $responseValidator): PromiseInterface {
            /** @var OperationAddress $magicOpAddress */
            $magicOpAddress = null;

            return $handler($this->validateRequest($request, $magicOpAddress), $options)->then(
                static function (ResponseInterface $response) use ($responseValidator, $magicOpAddress): ResponseInterface {
                    /** @var OperationAddress $magicOpAddress */
                    $responseValidator->validate($magicOpAddress, $response);

                    return $response;
                }
            );
        };
    }

    private function validateRequest(RequestInterface $request, ?OperationAddress &$magicOpAddress = null): RequestInterface
    {
        $magicOpAddress = $this->requestValidator->validate($request);

        return $request;
    }
}
