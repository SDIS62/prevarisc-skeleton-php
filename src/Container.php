<?php

namespace App;

use Laminas;
use GuzzleHttp;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;

final class Container extends Laminas\ServiceManager\ServiceManager
{
    public static function initWithDefaults(array $options = []) : self
    {
        // Setup service manager
        $container = new self([
            'services' => [
                'config' => $options,
            ],
            'invokables' => [
                // PSR-17 HTTP Message Factories
                RequestFactoryInterface::class       => GuzzleHttp\Psr7\HttpFactory::class,
                ServerRequestFactoryInterface::class => GuzzleHttp\Psr7\HttpFactory::class,
                ResponseFactoryInterface::class      => GuzzleHttp\Psr7\HttpFactory::class,
                StreamFactoryInterface::class        => GuzzleHttp\Psr7\HttpFactory::class,
                UploadedFileFactoryInterface::class  => GuzzleHttp\Psr7\HttpFactory::class,
                UriFactoryInterface::class           => GuzzleHttp\Psr7\HttpFactory::class,

                // PSR-18 HTTP Client implementations
                ClientInterface::class => GuzzleHttp\Client::class,
            ],
            'factories' => [
                Laminas\Di\ConfigInterface::class   => Laminas\Di\Container\ConfigFactory::class,
                Laminas\Di\InjectorInterface::class => Laminas\Di\Container\InjectorFactory::class,
            ],
        ]);

        return $container;
    }
}
