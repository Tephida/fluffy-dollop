<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Support;

use Error;

class Router
{
    /**
     * @var array $routes
     */
    private static array $routes = [];

    /**
     * @var string $requestUri
     */
    private static string $requestUri;

    /**
     * @var string $requestMethod
     */
    private static string $requestMethod;

    /**
     * @var string|null $requestHandler
     */
    private static ?string $requestHandler;

    /**
     * @var array|null $params
     */
    private static ?array $params = [];

    /**
     * @var string[] $placeholders
     */
    private static array $placeholders = [':seg' => '([^\/]+)', ':num' => '([0-9]+)', ':any' => '(.+)'];

    /**
     * @var string|null $controllerName
     */
    private static ?string $controllerName;

    /**
     * @var string|null $actionName
     */
    private static ?string $actionName;

    /**
     * Router constructor.
     * @param string $uri
     * @param string $method
     */
    public function __construct(string $uri, string $method = 'GET')
    {
        self::$requestUri = $uri;
        self::$requestMethod = $method;
    }

    /**
     * Factory method construct Router from global vars.
     * @return Router
     * @throws Error
     */
    public static function fromGlobals(): Router
    {
        $url = $_SERVER['HTTP_HOST'];
        $method = getenv('REQUEST_METHOD');
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri_data = $_SERVER['REQUEST_URI'];
        } elseif ($url) {
            $uri_data = $url;
        } else {
            throw new Error('err');
        }
        if (false !== $pos__ = strpos($uri_data, '?')) {
            $uri_data = substr($uri_data, 0, $pos__);
        }
        $uri_data = rawurldecode($uri_data);
        return new static($uri_data, $method);
    }

    /**
     * Current .
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Current processed URI.
     * @return string
     */
    public static function getRequestUri(): string
    {
        return self::$requestUri; // ?: '/';
    }

    /**
     * Request method.
     * @return string
     */
    public static function getRequestMethod(): string
    {
        return self::$requestMethod;
    }

    /**
     * Get Request handler.
     * @return string
     */
    public static function getRequestHandler(): string
    {
        return self::$requestHandler;
    }

    /**
     * Set Request handler.
     * @param $handler string|callable
     */
    final public function setRequestHandler(string|callable $handler)
    {
        self::$requestHandler = $handler;
    }

    /**
     * Request wildcard params.
     * @return array
     * @deprecated
     */
    public static function getParams(): array
    {
        return self::$params;
    }

    /**
     * Request params.
     * @return string
     */
    public static function getControllerName(): string
    {
        return self::$controllerName;
    }

    /**
     * Request params.
     * @return string
     */
    public static function getActionName(): string
    {
        return self::$actionName;
    }

    /**
     * Add route rule.
     *
     * @param string|array $route A URI route string or array
     * @param callable|string|null $handler Any callable or string with controller classname and action method like "ControllerClass@actionMethod"
     * @return Router
     */
    public function add(array|string $route, callable|null|string $handler = null): Router
    {
        if ($handler !== null && !is_array($route)) {
            $route = [$route => $handler];
        }
        self::$routes = array_merge(self::$routes, $route);
        return $this;
    }

    /**
     * Process requested URI.
     * @return bool
     */
    public function isFound(): bool
    {
        $uri_data = self::getRequestUri();

        /**
         *  if URI equals to route
         */
        if (isset(self::$routes[$uri_data])) {
            self::$requestHandler = self::$routes[$uri_data];
            return true;
        }

        $find_placeholder = array_keys(self::$placeholders);
        $replace = array_values(self::$placeholders);
        foreach (self::$routes as $route => $handler) {
            /**
             *  Replace wildcards by regex
             */
            if (str_contains($route, ':')) {
                $route = str_replace($find_placeholder, $replace, $route);
            }
            /**
             *  Route rule matched
             */
            if (preg_match('#^' . $route . '$#', $uri_data, $matches)) {
                self::$requestHandler = $handler;
                self::$params = array_slice($matches, 1);
                return true;
            }
        }
        return false;
    }

    /**
     * Execute Request Handler.
     * Запуск соответствующего действия/экшена/метода контроллера
     *
     * @param callable|null|string $handler
     * @param array $params
     * @return mixed
     * @throws Error
     */
    public function executeHandler(callable|null|string $handler = null, array $params = []): mixed
    {
        if ($handler === null) {
            throw new Error('err');
        }

        // execute action in callable
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
        // execute action in controllers
        if (strpos($handler, '@')) {
            $ca = explode('@', $handler);

            $controller_name = self::$controllerName = $ca['0'];
            $action = self::$actionName = $ca['1'];

            if (class_exists('\\Mozg\\modules\\' . $controller_name)) {
                if (!method_exists('\\Mozg\\modules\\' . $controller_name, $action)) {
                    throw new Error("Method '\\App\\Modules\\{$controller_name}::{$action}()' not found");
                }

                $class = '\\Mozg\\modules\\' . $controller_name;
                $controller = new $class();

                $params['params'] = '';
                $params = [$params];
                return call_user_func_array([$controller, $action], $params);
            }
            throw new Error("Class '{$controller_name}' not found");
        }
        throw new Error('Execute handler error');
    }
}