<?php
declare(strict_types=1);
/**
 * =====================================================
 * FRONT CONTROLLER
 * =====================================================
 * Point d'entrée unique de l'application
 * Toutes les requêtes passent par ce fichier
 */

// =====================================================
// 1. CHARGEMENT DE LA CONFIGURATION
// =====================================================
require_once __DIR__ . '/../config/config.php';

// =====================================================
// 2. CHARGEMENT DES HELPERS
// =====================================================
require_once HELPERS_PATH . '/functions.php';
require_once HELPERS_PATH . '/database.php';
require_once HELPERS_PATH . '/router.php';

// =====================================================
// 3. DÉMARRAGE DE LA SESSION
// =====================================================
startSession();

// =====================================================
// 4. GESTION DES ERREURS PERSONNALISÉES
// =====================================================
/**
 * Gestionnaire d'erreurs personnalisé
 */
function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    $message = "Erreur [$errno] : $errstr dans $errfile à la ligne $errline";
    logMessage($message, 'error');

    if (ENVIRONMENT === 'development') {
        echo "<div style='background:#f8d7da;color:#721c24;padding:15px;margin:10px;border:1px solid #f5c6cb;border-radius:5px;'>";
        echo "<strong>Erreur PHP :</strong> $errstr<br>";
        echo "<strong>Fichier :</strong> $errfile<br>";
        echo "<strong>Ligne :</strong> $errline";
        echo "</div>";
    }

    // Ne pas exécuter le gestionnaire d'erreurs interne de PHP
    return true;
}

set_error_handler('customErrorHandler');

// =====================================================
// 5. GESTION DES EXCEPTIONS
// =====================================================
/**
 * Gestionnaire d'exceptions personnalisé
 */
function customExceptionHandler($exception)
{
    $message = "Exception : " . $exception->getMessage() . " dans " .
        $exception->getFile() . " à la ligne " . $exception->getLine();
    logMessage($message, 'error');

    if (ENVIRONMENT === 'development') {
        echo "<div style='background:#f8d7da;color:#721c24;padding:15px;margin:10px;border:1px solid #f5c6cb;border-radius:5px;'>";
        echo "<strong>Exception :</strong> " . $exception->getMessage() . "<br>";
        echo "<strong>Fichier :</strong> " . $exception->getFile() . "<br>";
        echo "<strong>Ligne :</strong> " . $exception->getLine() . "<br>";
        echo "<strong>Trace :</strong><pre>" . $exception->getTraceAsString() . "</pre>";
        echo "</div>";
    } else {
        echo "Une erreur est survenue. Veuillez réessayer plus tard.";
    }
}

set_exception_handler('customExceptionHandler');

// =====================================================
// 6. ROUTES PERSONNALISÉES (OPTIONNEL)
// =====================================================
// Exemples de routes personnalisées
// addRoute('blog/article/:id', 'blog', 'show');
// addRoute('users/:username', 'users', 'profile');

// =====================================================
// 7. LANCEMENT DU ROUTEUR
// =====================================================
dispatch();

// =====================================================
// FIN DU FRONT CONTROLLER
// =====================================================
