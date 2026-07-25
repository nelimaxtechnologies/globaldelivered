<?php
/**
 * Global Delivered Logistics - Router
 * 
 * Handles URL routing, middleware execution, and controller dispatch.
 * Supports RESTful routes, route groups, and middleware.
 */

namespace App\Core;

class Router
{
    private static array $routes = [];
    private static array $groupAttributes = [];
    private static array $middleware = [];

    /**
     * Register a GET route
     */
    public static function get(string $uri, string $handler): void
    {
        self::addRoute('GET', $uri, $handler);
    }

    /**
     * Register a POST route
     */
    public static function post(string $uri, string $handler): void
    {
        self::addRoute('POST', $uri, $handler);
    }

    /**
     * Register a PUT route
     */
    public static function put(string $uri, string $handler): void
    {
        self::addRoute('PUT', $uri, $handler);
    }

    /**
     * Register a DELETE route
     */
    public static function delete(string $uri, string $handler): void
    {
        self::addRoute('DELETE', $uri, $handler);
    }

    /**
     * Register routes for all HTTP methods
     */
    public static function any(string $uri, string $handler): void
    {
        self::addRoute('GET|POST|PUT|DELETE|PATCH', $uri, $handler);
    }

    /**
     * Register resourceful routes
     */
    public static function resource(string $name, string $controller): void
    {
        $base = trim($name, '/');
        self::get($base, "{$controller}@index");
        self::get("{$base}/create", "{$controller}@create");
        self::post($base, "{$controller}@store");
        self::get("{$base}/{id}", "{$controller}@show");
        self::get("{$base}/{id}/edit", "{$controller}@edit");
        self::put("{$base}/{id}", "{$controller}@update");
        self::delete("{$base}/{id}", "{$controller}@destroy");
    }

    /**
     * Register AJAX/API routes
     */
    public static function apiResource(string $name, string $controller): void
    {
        $base = trim($name, '/');
        self::get($base, "{$controller}@index");
        self::post($base, "{$controller}@store");
        self::get("{$base}/{id}", "{$controller}@show");
        self::put("{$base}/{id}", "{$controller}@update");
        self::delete("{$base}/{id}", "{$controller}@destroy");
    }

    /**
     * Group routes with shared attributes
     */
    public static function group(array $attributes, callable $callback): void
    {
        $previousGroup = self::$groupAttributes;
        self::$groupAttributes = array_merge_recursive(self::$groupAttributes, $attributes);
        $callback();
        self::$groupAttributes = $previousGroup;
    }

    /**
     * Add middleware to routes
     */
    public static function middleware(string $middleware): self
    {
        self::$middleware[] = $middleware;
        return new self();
    }

    /**
     * Add a route to the collection
     */
    private static function addRoute(string $methods, string $uri, string $handler): void
    {
        $prefix = self::$groupAttributes['prefix'] ?? '';
        $uri = '/' . trim($prefix . '/' . trim($uri, '/'), '/');
        $uri = $uri ?: '/';
        
        $middleware = array_merge(
            self::$middleware,
            self::$groupAttributes['middleware'] ?? []
        );
        
        self::$middleware = [];
        
        $methods = explode('|', $methods);
        foreach ($methods as $method) {
            self::$routes[] = [
                'method'     => strtoupper(trim($method)),
                'uri'        => $uri,
                'handler'    => $handler,
                'middleware' => $middleware,
                'name'       => self::$groupAttributes['as'] ?? null,
            ];
        }
    }

    /**
     * Dispatch the current request
     */
    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Method spoofing: support _method field for PUT/DELETE from HTML forms
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path
        $basePath = '/' . basename(dirname(__DIR__, 2));
        if (str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');
        
        // Handle CORS preflight
        if ($method === 'OPTIONS') {
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN');
            exit;
        }
        
        $route = self::matchRoute($method, $uri);
        
        if (!$route) {
            if (str_starts_with($uri, '/api/')) {
                json_response(['success' => false, 'message' => 'Route not found'], 404);
            }
            http_response_code(404);
            require VIEWS_PATH . '/errors/404.php';
            exit;
        }
        
        // Execute middleware
        foreach ($route['middleware'] as $middleware) {
            self::executeMiddleware($middleware);
        }
        
        // Parse handler
        [$controllerName, $action] = explode('@', $route['handler']);
        $controllerClass = "App\\Controllers\\{$controllerName}";
        
        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller not found: {$controllerClass}");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Action not found: {$controllerClass}@{$action}");
        }
        
        // Extract route parameters
        $params = self::extractParams($route['uri'], $uri);
        
        // Call controller action
        call_user_func_array([$controller, $action], $params);
    }

    /**
     * Match a route
     */
    private static function matchRoute(string $method, string $uri): ?array
    {
        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) continue;
            
            $pattern = self::uriToRegex($route['uri']);
            if (preg_match($pattern, $uri)) {
                return $route;
            }
        }
        
        return null;
    }

    /**
     * Convert URI pattern to regex
     */
    private static function uriToRegex(string $uri): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $uri);
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    /**
     * Extract parameters from URI
     */
    private static function extractParams(string $routeUri, string $requestUri): array
    {
        $params = [];
        $routeParts = explode('/', trim($routeUri, '/'));
        $uriParts = explode('/', trim($requestUri, '/'));
        
        foreach ($routeParts as $i => $part) {
            if (preg_match('/\{(\w+)\}/', $part, $matches)) {
                $params[$matches[1]] = $uriParts[$i] ?? null;
            }
        }
        
        return $params;
    }

    /**
     * Execute middleware
     */
    private static function executeMiddleware(string $middleware): void
    {
        $middlewareClass = "App\\Middleware\\{$middleware}";
        
        if (class_exists($middlewareClass)) {
            $instance = new $middlewareClass();
            if (method_exists($instance, 'handle')) {
                $instance->handle();
            }
        }
    }

    /**
     * Get all registered routes
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }
}
