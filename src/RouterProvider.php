<?php namespace NSRosenqvist\Soma\Router;

use Soma\ServiceProvider;
use Psr\Container\ContainerInterface;

use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

class RouterProvider extends ServiceProvider
{
    public function ready(ContainerInterface $c)
    {
        // A hook for registering routes
        $router = $c->get('router');
        event('router.init', $router);

        // Get request
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );

        event('router.request', $request);

        // Process response
        $response = $c->get('router')->dispatch($request);
        event('router.response', $respone);

        (new SapiEmitter)->emit($response);
    }

    public function getFactories() : array
    {
        return [
            'router' => function(ContainerInterface $c) {
                $strategy = (new ApplicationStrategy)->setContainer($c);
                $router = (new Router)->setStrategy($strategy);

                return $router;
            },
        ];
    }
}