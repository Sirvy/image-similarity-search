<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList;
        $router->addRoute('api/images', 'Api:Images:default');
        $router->addRoute('api/sample', 'Api:Sample:default');
        $router->addRoute('api/apply-search', 'Api:ApplySearch:default');
        $router->addRoute('<presenter>/<action>[/<id>]', 'Index:default');

        return $router;
    }
}
