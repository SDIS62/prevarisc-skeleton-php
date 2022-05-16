<?php

namespace App\Test\Http;

use App;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestFactoryInterface;

class HttpPipelineTest extends TestCase
{
    public function test() : void
    {
        $container              = App\Container::initWithDefaults();
        $router                 = new App\Http\HttpPipeline($container);
        $server_request_factory = $container->get(ServerRequestFactoryInterface::class);
        $server_request         = $server_request_factory->createServerRequest('GET', '/_healthcheck');
        $response               = $router->handle($server_request);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
