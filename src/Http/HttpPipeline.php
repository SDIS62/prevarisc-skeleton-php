<?php

namespace App\Http;

use Exception;
use Laminas\Di;
use League\Route\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HttpPipeline implements RequestHandlerInterface
{
    /**
     * Initialisation du pipeline HTTP avec un Container servant à l'injection des dépendances.
     */
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    /**
     * La requête passe dans un pipeline HTTP afin de produire une réponse.
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // Build default HTTP router
        $router = new Router();

        // Injector reponsible of auto wiring strategy which implements constructor injection
        $injector = $this->container->get(Di\InjectorInterface::class);
        if (!($injector instanceof Di\InjectorInterface)) {
            throw new Exception("Injector must be an instance of Di\InjectorInterface");
        }

        // Healthcheck routes
        $router->head('/_healthcheck', $injector->create(Controller\HealthCheck::class));
        $router->get('/_healthcheck', $injector->create(Controller\HealthCheck::class));

        // Dispatch the request in the HTTP router
        return $router->dispatch($request);
    }
}
