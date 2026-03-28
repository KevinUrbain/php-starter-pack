$
<?php
/**
 * =====================================================
 * ROUTEUR PROCÉDURAL PROFESSIONNEL
 * =====================================================
 * Gère le routage des URLs vers les contrôleurs et actions
 * Supporte :
 * - URLs propres (/controller/action/param1/param2)
 * - Paramètres dynamiques
 * - Routes personnalisées
 * - Méthodes HTTP (GET, POST, PUT, DELETE)
 */

/**
 * Parse l'URL et retourne les segments
 * 
 * @return array Tableau contenant [controller, action, params]
 */
function parseUrl()
{
    // Récupérer l'URL depuis le paramètre GET 'url'
    $url = $_GET['url'] ?? '';

    // Nettoyer l'URL
    $url = trim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);

    // Si URL vide, retourner les valeurs par défaut
    if (empty($url)) {
        return [
            'controller' => DEFAULT_CONTROLLER,
            'action' => DEFAULT_ACTION,
            'params' => []
        ];
    }

    // Diviser l'URL en segments
    $segments = explode('/', $url);

    // Extraire contrôleur, action et paramètres
    $controller = !empty($segments[0]) ? $segments[0] : DEFAULT_CONTROLLER;
    $action = isset($segments[1]) && !empty($segments[1]) ? $segments[1] : DEFAULT_ACTION;
    $params = array_slice($segments, 2);

    return [
        'controller' => $controller,
        'action' => $action,
        'params' => $params
    ];
}

/**
 * Vérifie si le contrôleur existe
 * 
 * @param string $controller Nom du contrôleur
 * @return bool
 */
function controllerExists($controller)
{
    $controllerFile = CONTROLLERS_PATH . '/' . $controller . '.php';
    return file_exists($controllerFile);
}

/**
 * Charge un contrôleur
 * 
 * @param string $controller Nom du contrôleur
 * @return bool Succès du chargement
 */
function loadController($controller)
{
    $controllerFile = CONTROLLERS_PATH . '/' . $controller . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return true;
    }

    return false;
}

/**
 * Vérifie si une action (fonction) existe dans le contrôleur
 * 
 * @param string $action Nom de l'action
 * @return bool
 */
function actionExists(string $action)
{
    return function_exists($action);
}

/**
 * Exécute l'action du contrôleur avec les paramètres
 * 
 * @param string $action Nom de l'action
 * @param array $params Paramètres à passer
 * @return void
 */
function executeAction(string $action, array $params = [])
{
    if (function_exists($action)) {
        // Appeler la fonction avec les paramètres
        call_user_func_array($action, $params);
    }
}

/**
 * Gère l'erreur 404
 * 
 * @return void
 */
function handle404()
{
    http_response_code(404);

    // Charger le contrôleur d'erreur
    if (loadController(ERROR_404_CONTROLLER)) {
        if (actionExists(ERROR_404_ACTION)) {
            executeAction(ERROR_404_ACTION);
            return;
        }
    }

    // Si pas de contrôleur d'erreur, afficher message simple
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>404 - Page non trouvée</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            h1 { font-size: 72px; margin: 0; color: #e74c3c; }
            p { font-size: 18px; color: #555; }
            a { color: #3498db; text-decoration: none; }
        </style>
    </head>
    <body>
        <h1>404</h1>
        <p>La page que vous recherchez n'existe pas.</p>
        <a href='" . BASE_URL . "'>Retour à l'accueil</a>
    </body>
    </html>";
}

/**
 * Génère une URL propre
 * 
 * @param string $controller Nom du contrôleur
 * @param string $action Nom de l'action
 * @param array $params Paramètres supplémentaires
 * @return string URL complète
 */
function url(string $controller = '', string $action = '', array $params = [])
{
    $url = BASE_URL;

    if (!empty($controller)) {
        $url .= '/' . $controller;
    }

    if (!empty($action)) {
        $url .= '/' . $action;
    }

    if (!empty($params)) {
        foreach ($params as $param) {
            $url .= '/' . $param;
        }
    }

    return $url;
}

/**
 * Redirige vers une URL
 * 
 * @param string $controller Nom du contrôleur
 * @param string $action Nom de l'action
 * @param array $params Paramètres supplémentaires
 * @return void
 */
function redirect(string $controller = '', string $action = '', array $params = [])
{
    $url = url($controller, $action, $params);
    header("Location: $url");
    exit();
}

/**
 * Vérifie la méthode HTTP de la requête
 * 
 * @param string $method Méthode attendue (GET, POST, PUT, DELETE)
 * @return bool
 */
function isMethod(string $method)
{
    return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
}

/**
 * Récupère la méthode HTTP actuelle
 * 
 * @return string
 */
function getMethod()
{
    return $_SERVER['REQUEST_METHOD'];
}

/**
 * Lance le routeur et distribue la requête
 * 
 * @return void
 */
function dispatch()
{
    // Parser l'URL
    $route = parseUrl();

    $controller = $route['controller'];
    $action = $route['action'];
    $params = $route['params'];

    // Vérifier si le contrôleur existe
    if (!controllerExists($controller)) {
        handle404();
        return;
    }

    // Charger le contrôleur
    if (!loadController($controller)) {
        handle404();
        return;
    }

    // Vérifier si l'action existe
    if (!actionExists($action)) {
        handle404();
        return;
    }

    // Exécuter l'action avec les paramètres
    executeAction($action, $params);
}

/**
 * Système de routes personnalisées (optionnel, avancé)
 * Permet de définir des routes spécifiques
 */
$customRoutes = [];

/**
 * Ajoute une route personnalisée
 * 
 * @param string $pattern Pattern de l'URL (ex: 'blog/article/:id')
 * @param string $controller Contrôleur cible
 * @param string $action Action cible
 */
function addRoute(string $pattern, string $controller, string $action)
{
    global $customRoutes;
    $customRoutes[$pattern] = [
        'controller' => $controller,
        'action' => $action
    ];
}

/**
 * Trouve une route personnalisée correspondante
 * 
 * @param string $url URL à matcher
 * @return array|null Route trouvée ou null
 */
function matchCustomRoute(string $url)
{
    global $customRoutes;

    foreach ($customRoutes as $pattern => $route) {
        // Convertir le pattern en regex
        $pattern = preg_replace('/:([a-zA-Z0-9_]+)/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $url, $matches)) {
            // Extraire les paramètres nommés
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            return [
                'controller' => $route['controller'],
                'action' => $route['action'],
                'params' => $params
            ];
        }
    }

    return null;
}
