<?php
/**
 * =====================================================
 * FONCTIONS HELPER GÉNÉRALES
 * =====================================================
 * Fonctions utilitaires réutilisables dans toute l'application
 */

/**
 * =====================================================
 * SÉCURITÉ
 * =====================================================
 */

/**
 * Échappe les caractères HTML pour éviter les failles XSS
 * 
 * @param string $string Chaîne à échapper
 * @return string Chaîne sécurisée
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Nettoie une chaîne de caractères
 * 
 * @param string $string Chaîne à nettoyer
 * @return string Chaîne nettoyée
 */
function clean($string) {
    return trim(strip_tags($string));
}

/**
 * Valide un email
 * 
 * @param string $email Email à valider
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Génère un token CSRF
 * 
 * @return string Token généré
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie un token CSRF
 * 
 * @param string $token Token à vérifier
 * @return bool
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * =====================================================
 * SESSIONS ET MESSAGES FLASH
 * =====================================================
 */

/**
 * Démarre la session si elle n'est pas déjà démarrée
 * 
 * @return void
 */
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Définit un message flash
 * 
 * @param string $type Type de message (success, error, warning, info)
 * @param string $message Contenu du message
 * @return void
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

/**
 * Récupère et supprime un message flash
 * 
 * @param string $type Type de message
 * @return string|null Message ou null
 */
function getFlashMessage($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

/**
 * Vérifie si un message flash existe
 * 
 * @param string $type Type de message
 * @return bool
 */
function hasFlashMessage($type) {
    return isset($_SESSION['flash'][$type]);
}

/**
 * =====================================================
 * VUES
 * =====================================================
 */

/**
 * Charge une vue avec des données
 * 
 * @param string $view Nom du fichier de vue (sans .php)
 * @param array $data Données à passer à la vue
 * @return void
 */
function view($view, $data = []) {
    // Extraire les données pour les rendre accessibles comme variables
    extract($data);
    
    // Construire le chemin du fichier de vue
    $viewFile = VIEWS_PATH . '/' . $view . '.php';
    
    // Vérifier si la vue existe
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        die("La vue '$view' n'existe pas.");
    }
}

/**
 * Charge une vue partielle (header, footer, etc.)
 * 
 * @param string $partial Nom du fichier partiel
 * @param array $data Données à passer
 * @return void
 */
function partial($partial, $data = []) {
    extract($data);
    $partialFile = VIEWS_PATH . '/partials/' . $partial . '.php';
    
    if (file_exists($partialFile)) {
        require_once $partialFile;
    }
}

/**
 * =====================================================
 * DÉBOGAGE
 * =====================================================
 */

/**
 * Affiche des données de manière formatée (debug)
 * 
 * @param mixed $data Données à afficher
 * @param bool $die Arrêter l'exécution après affichage
 * @return void
 */
function dd($data, $die = true) {
    echo '<pre style="background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 5px; margin: 10px; font-family: monospace;">';
    print_r($data);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * Dump formaté sans arrêt
 * 
 * @param mixed $data
 * @return void
 */
function dump($data) {
    dd($data, false);
}

/**
 * =====================================================
 * VALIDATION
 * =====================================================
 */

/**
 * Valide les données d'un formulaire
 * 
 * @param array $data Données à valider
 * @param array $rules Règles de validation
 * @return array Tableau des erreurs (vide si tout est valide)
 */
function validate($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $fieldRules) {
        $value = isset($data[$field]) ? $data[$field] : '';
        
        foreach ($fieldRules as $rule) {
            // Required
            if ($rule === 'required' && empty($value)) {
                $errors[$field][] = "Le champ $field est obligatoire.";
            }
            
            // Email
            if ($rule === 'email' && !empty($value) && !isValidEmail($value)) {
                $errors[$field][] = "Le champ $field doit être un email valide.";
            }
            
            // Min length
            if (strpos($rule, 'min:') === 0) {
                $min = (int) substr($rule, 4);
                if (strlen($value) < $min) {
                    $errors[$field][] = "Le champ $field doit contenir au moins $min caractères.";
                }
            }
            
            // Max length
            if (strpos($rule, 'max:') === 0) {
                $max = (int) substr($rule, 4);
                if (strlen($value) > $max) {
                    $errors[$field][] = "Le champ $field ne peut pas dépasser $max caractères.";
                }
            }
        }
    }
    
    return $errors;
}

/**
 * =====================================================
 * UTILITAIRES
 * =====================================================
 */

/**
 * Formate une date
 * 
 * @param string $date Date à formater
 * @param string $format Format de sortie
 * @return string Date formatée
 */
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

/**
 * Tronque un texte
 * 
 * @param string $text Texte à tronquer
 * @param int $length Longueur maximale
 * @param string $suffix Suffixe à ajouter
 * @return string Texte tronqué
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Génère un slug à partir d'une chaîne
 * 
 * @param string $string Chaîne à convertir
 * @return string Slug
 */
function slugify($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Vérifie si l'utilisateur est connecté
 * 
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Récupère l'utilisateur connecté
 * 
 * @return array|null
 */
function currentUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

/**
 * Enregistre un log
 * 
 * @param string $message Message à logger
 * @param string $level Niveau (info, warning, error)
 * @return void
 */
function logMessage($message, $level = 'info') {
    $logFile = LOGS_PATH . '/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
