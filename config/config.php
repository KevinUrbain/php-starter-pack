<?php
/**
 * =====================================================
 * FICHIER DE CONFIGURATION PRINCIPAL
 * =====================================================
 * Ce fichier contient toutes les configurations de l'application
 * Séparation claire entre environnements (dev/prod)
 */

// =====================================================
// ENVIRONNEMENT
// =====================================================
define('ENVIRONMENT', 'development'); // 'development' ou 'production'

// =====================================================
// CHEMINS DE BASE
// =====================================================
// Chemin racine du projet (répertoire parent de 'public')
define('ROOT_PATH', dirname(__DIR__));

// Chemin du dossier public
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Chemin du dossier src
define('SRC_PATH', ROOT_PATH . '/src');

// Chemins spécifiques
define('CONTROLLERS_PATH', SRC_PATH . '/controllers');
define('VIEWS_PATH', SRC_PATH . '/views');
define('MODELS_PATH', SRC_PATH . '/models');
define('HELPERS_PATH', SRC_PATH . '/helpers');
define('LOGS_PATH', ROOT_PATH . '/logs');

// =====================================================
// URLs DE BASE
// =====================================================
// URL de base de votre site (à adapter selon votre environnement)
// Exemples :
// - Localhost : 'http://localhost/php-starter-pack/public'
// - Serveur local avec virtual host : 'http://monsite.local'
// - Production : 'https://www.monsite.com'
define('BASE_URL', 'http://localhost/php-starter-pack/public');

// URL des assets
define('CSS_URL', BASE_URL . '/css');
define('JS_URL', BASE_URL . '/js');
define('IMAGES_URL', BASE_URL . '/images');

// =====================================================
// BASE DE DONNÉES
// =====================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'php_starter');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// =====================================================
// CONFIGURATION PHP
// =====================================================
// Fuseau horaire
date_default_timezone_set('Europe/Brussels');

// Gestion des erreurs selon l'environnement
if (ENVIRONMENT === 'development') {
    // Mode développement : afficher toutes les erreurs
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // Mode production : masquer les erreurs, les logger
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', LOGS_PATH . '/php-errors.log');
}

// =====================================================
// SÉCURITÉ
// =====================================================
// Clé secrète pour les sessions et tokens (À CHANGER EN PRODUCTION!)
define('SECRET_KEY', 'votre-cle-secrete-tres-longue-et-aleatoire-12345');

// Durée de vie des sessions (en secondes) - 2 heures par défaut
define('SESSION_LIFETIME', 7200);

// Configuration des sessions sécurisées
ini_set('session.cookie_httponly', 1); // Protection XSS
ini_set('session.cookie_samesite', 'Lax'); // Protection CSRF
if (ENVIRONMENT === 'production') {
    ini_set('session.cookie_secure', 1); // HTTPS uniquement en prod
}

// =====================================================
// CONFIGURATION APPLICATION
// =====================================================
// Nom de l'application
define('APP_NAME', 'PHP Starter Pack');

// Version
define('APP_VERSION', '1.0.0');

// Email de contact
define('CONTACT_EMAIL', 'contact@monsite.com');

// Nombre d'éléments par page (pagination)
define('ITEMS_PER_PAGE', 10);

// =====================================================
// ROUTES PAR DÉFAUT
// =====================================================
// Contrôleur par défaut (page d'accueil)
define('DEFAULT_CONTROLLER', 'home');

// Action par défaut
define('DEFAULT_ACTION', 'index');

// Page 404
define('ERROR_404_CONTROLLER', 'error');
define('ERROR_404_ACTION', 'notFound');

// =====================================================
// PARAMÈTRES UPLOADS
// =====================================================
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('MAX_UPLOAD_SIZE', 5242880); // 5 Mo en octets
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// =====================================================
// FIN DE CONFIGURATION
// =====================================================
