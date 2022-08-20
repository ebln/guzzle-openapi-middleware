<?php

declare(strict_types=1);

namespace Ebln\Test\Guzzle\OpenApi;

use Ebln\Guzzle\OpenApi\Middleware;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidBody;
use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidPath;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class MiddlewareTest extends TestCase
{
    public function testValid(): void
    {
        self::assertInstanceOf(Response::class, $this->doJsonRequest('/test/12', '{"requestId" : 1}', '{"responseId" : 1}'));
    }

    public function testFailingPath(): void
    {
        $this->expectException(InvalidPath::class);
        $this->doJsonRequest('/test/7', '{"requestId" : 1}', '{"responseId" : 1}');
    }

    public function testFailingRequest(): void
    {
        $this->expectException(InvalidBody::class);
        $this->doJsonRequest('/test/11', '{"requestId" : "error"}', '{"responseId" : 15}');
    }

    public function testFailingResponse(): void
    {
        $this->expectException(InvalidBody::class);
        $this->doJsonRequest('/test/11', '{"requestId" : 1}', '{"responseId" : "error"}');
    }

    private const DEFAULT_YAML = <<<END
        openapi: 3.0.3
        info:
          title: Testing
          version: 0.0.1
        paths:
          /test/{param}:
            post:
              parameters:
                - name: param
                  in: path
                  required: true
                  schema:
                    type : integer
                    format: int64
                    minimum: 9
              requestBody:
                description: Question
                content:
                  application/json:
                    schema:
                      type: object
                      properties:
                        requestId:
                          type: integer
                          format: int64
                required: true
              responses:
                '200':
                  description: Answer
                  content:
                    application/json:
                      schema:
                        type: object
                        properties:
                          responseId:
                            type: integer
                            format: int64
                          content:
                            type: string
        END;

    private function doJsonRequest(string $url, string $requestJson, string $responseJson): ResponseInterface
    {
        return $this->getClient(
            self::DEFAULT_YAML,
            new Response(200, ['Content-Type' => 'application/json'], $responseJson)
        )->request(
            'POST', $url,
            [
                'body'    => $requestJson,
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );
    }

    private function getClient(string $yaml, Response $response): Client
    {
        $builder = new ValidatorBuilder();
        $builder->fromYaml($yaml);
        $middleware   = new Middleware($builder->getRequestValidator(), $builder->getResponseValidator());
        $mock         = new MockHandler([$response]);
        $handlerStack = new HandlerStack($mock);
        $handlerStack->push($middleware);

        return new Client(['handler' => $handlerStack]);
    }
}
