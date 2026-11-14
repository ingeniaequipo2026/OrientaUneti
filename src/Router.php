<?php
/**
 * Enrutador Básico en PHP Puro
 * Soporta métodos GET y POST, parámetros dinámicos ({id}) y middleware de autenticación
 */

class Router {
    private array $routes = [];

    public function get(string $path, $handler, array $middlewares = []): void {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): void {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, $handler, array $middlewares): void {
        $path = '/' . trim($path, '/');
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[] = [
            'method'      => $method,
            'path'        => $path,
            'pattern'     => $pattern,
            'handler'     => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(): void {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        $uri = '';
        if (isset($_GET['r']) && !empty($_GET['r'])) {
            $uri = '/' . trim($_GET['r'], '/');
        } elseif (isset($_GET['url']) && !empty($_GET['url'])) {
            $uri = '/' . trim($_GET['url'], '/');
        } else {
            $parsedUrl = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $parsedUrl = str_replace('\\', '/', $parsedUrl);

            // Remover la carpeta del script si está en un subdirectorio
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            if ($scriptDir !== '/' && $scriptDir !== '.' && !empty($scriptDir)) {
                if (str_starts_with($parsedUrl, $scriptDir)) {
                    $parsedUrl = substr($parsedUrl, strlen($scriptDir));
                }
            }

            // Limpieza de prefijos comunes en Apache
            $parsedUrl = preg_replace('#^/public#i', '', $parsedUrl);
            $parsedUrl = preg_replace('#^/index\.php#i', '', $parsedUrl);
            $uri = '/' . trim($parsedUrl, '/');
        }

        if ($uri === '') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($route['pattern'], $uri, $matches)) {
                foreach ($route['middlewares'] as $middleware) {
                    if (is_callable($middleware)) {
                        $middleware();
                    } elseif (class_exists($middleware)) {
                        $instance = new $middleware();
                        if (method_exists($instance, 'handle')) {
                            $instance->handle();
                        }
                    }
                }

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (is_callable($route['handler'])) {
                    call_user_func_array($route['handler'], $params);
                    return;
                }

                if (is_string($route['handler']) && str_contains($route['handler'], '@')) {
                    [$controllerName, $action] = explode('@', $route['handler']);
                    $controllerFile = __DIR__ . '/Controllers/' . $controllerName . '.php';
                    
                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                    }

                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $action)) {
                            call_user_func_array([$controller, $action], $params);
                            return;
                        }
                    }
                }

                http_response_code(500);
                echo "Error interno: El controlador o acción no existe.";
                return;
            }
        }

        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
    }
}
