<?php

namespace App\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class HealthCheck
{
    public function __construct(
        private ResponseFactoryInterface $response_factory
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args = []) : ResponseInterface
    {
        return $this->response_factory->createResponse(200);
    }
}
