<?php
/**
 * =====================================================
 * CONTRÔLEUR HOME
 * =====================================================
 * Gère la page d'accueil et les actions associées
 */

/**
 * Action par défaut : affiche la page d'accueil
 * 
 * @return void
 */
function index()
{
    // Préparer les données pour la vue
    $data = [
        'title' => 'Bienvenue',
        'message' => 'Votre application PHP est prête !',
        'features' => [
            'Front Controller unique',
            'Routeur procédural puissant',
            'URLs propres et SEO-friendly',
            'Architecture MVC-like',
            'Helpers réutilisables',
            'Système de templates',
            'Gestion des sessions',
            'Messages flash',
            'Sécurité intégrée (CSRF, XSS)',
            'Base de données MySQL et Requêtes préparées (PDO)'
        ]
    ];

    // Charger la vue
    view('home/index', $data);
}

/**
 * Page À propos
 * 
 * @return void
 */
function about()
{
    $data = [
        'title' => 'À propos',
        'description' => 'PHP Starter Pack - Framework procédural professionnel'
    ];

    view('home/about', $data);
}

/**
 * Page Contact
 * 
 * @return void
 */
function contact()
{
    // Si formulaire soumis
    if (isMethod('POST')) {
        // Récupérer les données
        $name = clean($_POST['name'] ?? '');
        $email = clean($_POST['email'] ?? '');
        $message = clean($_POST['message'] ?? '');

        // Valider
        $errors = validate($_POST, [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'message' => ['required', 'min:10']
        ]);

        // Si pas d'erreurs
        if (empty($errors)) {
            // Ici : envoyer l'email, enregistrer en BDD, etc.

            // Message de succès
            setFlashMessage('success', 'Votre message a bien été envoyé !');

            // Redirection
            redirect('home', 'contact');
        } else {
            // Afficher les erreurs
            $data = [
                'title' => 'Contact',
                'errors' => $errors,
                'old' => $_POST
            ];

            view('home/contact', $data);
            return;
        }
    }

    // Affichage du formulaire
    $data = [
        'title' => 'Contact'
    ];

    view('home/contact', $data);
}
