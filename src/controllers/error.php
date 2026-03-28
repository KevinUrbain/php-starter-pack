<?php
/**
 * =====================================================
 * CONTRÔLEUR ERROR
 * =====================================================
 * Gère les pages d'erreur
 */

/**
 * Page 404 - Not Found
 * 
 * @return void
 */
function notFound() {
    $data = [
        'title' => '404 - Page non trouvée',
        'message' => 'La page que vous recherchez n\'existe pas.',
        'code' => 404
    ];
    
    view('error/404', $data);
}

/**
 * Page 403 - Forbidden
 * 
 * @return void
 */
function forbidden() {
    http_response_code(403);
    
    $data = [
        'title' => '403 - Accès refusé',
        'message' => 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.',
        'code' => 403
    ];
    
    view('error/403', $data);
}

/**
 * Page 500 - Internal Server Error
 * 
 * @return void
 */
function serverError() {
    http_response_code(500);
    
    $data = [
        'title' => '500 - Erreur serveur',
        'message' => 'Une erreur est survenue sur le serveur.',
        'code' => 500
    ];
    
    view('error/500', $data);
}
