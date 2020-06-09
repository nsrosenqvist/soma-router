# SOMA Router

## Installation

```sh
composer require nsrosenqvist/soma-router
```

## Usage

Register the service provider, preferably alias `NSRosenqvist\Soma\Router\Facades\Router` to `Router`, and then simply register your routes using the Facade. The API is found [here](https://route.thephpleague.com/4.x/routes/).

The system event `router.init` is a great place to register your routes.

```php
namespace MyApp;

use Soma\ServiceProvider;
use Psr\Container\ContainerInterface;

use Laminas\Diactoros\Response;

class RouterProvider extends ServiceProvider
{
    public function boot(ContainerInterface $c)
    {
        listen('router.init', function($router) use ($c) {
            // map a route
            $router->map('GET', '/', function (ServerRequestInterface $request) : ResponseInterface {
                $response = new Response;
                $response->getBody()->write('<h1>Hello, World!</h1>');
                return $response;
            });

            // or include a file with all route definitions
            include "path/to/routes.php";
        });
    }
}
```

## License

MIT