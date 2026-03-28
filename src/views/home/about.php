<?php partial('header', ['title' => $title]); ?>

<div class="container">
    <h1><?= escape($title) ?></h1>
    
    <div class="content-block">
        <h2>Qu'est-ce que <?= APP_NAME ?> ?</h2>
        <p>
            <?= escape($description) ?>. C'est un starter pack complet pour démarrer
            rapidement vos projets web en PHP procédural avec une architecture propre
            et des bonnes pratiques professionnelles.
        </p>
    </div>
    
    <div class="content-block">
        <h2>Pourquoi utiliser ce starter pack ?</h2>
        <ul>
            <li><strong>Architecture claire</strong> : Séparation des responsabilités (MVC-like)</li>
            <li><strong>URLs propres</strong> : SEO-friendly grâce au système de routage</li>
            <li><strong>Sécurité intégrée</strong> : Protection XSS, CSRF, injections SQL</li>
            <li><strong>Évolutif</strong> : Facilite la transition vers l'orienté objet</li>
            <li><strong>Bonnes pratiques</strong> : Code professionnel et documenté</li>
            <li><strong>Helpers utiles</strong> : Fonctions réutilisables pour gagner du temps</li>
        </ul>
    </div>
    
    <div class="content-block">
        <h2>Technologies utilisées</h2>
        <ul>
            <li>PHP 7.4+ (procédural)</li>
            <li>MySQLi pour la base de données</li>
            <li>Apache avec mod_rewrite</li>
            <li>HTML5, CSS3, JavaScript</li>
        </ul>
    </div>
    
    <div class="content-block">
        <h2>Structure du projet</h2>
        <pre><code>php-starter-pack/
├── config/              # Configuration
│   └── config.php
├── logs/                # Fichiers de log
├── public/              # Dossier web racine
│   ├── css/
│   ├── js/
│   ├── images/
│   ├── .htaccess        # Réécriture d'URL
│   └── index.php        # Front Controller
└── src/
    ├── controllers/     # Contrôleurs
    ├── views/           # Vues (templates)
    ├── models/          # Modèles (logique données)
    └── helpers/         # Fonctions utilitaires</code></pre>
    </div>
</div>

<?php partial('footer'); ?>
