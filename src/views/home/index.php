<?php partial('header', ['title' => $title]); ?>

<div class="container">
    <div class="hero">
        <h1><?= escape($title) ?> sur <?= APP_NAME ?> 🚀</h1>
        <p class="subtitle"><?= escape($message) ?></p>
    </div>
    
    <div class="features">
        <h2>Fonctionnalités</h2>
        <div class="features-grid">
            <?php foreach ($features as $feature): ?>
                <div class="feature-card">
                    <span class="checkmark">✓</span>
                    <?= escape($feature) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="getting-started">
        <h2>Démarrage rapide</h2>
        <div class="code-block">
            <h3>1. Créer un nouveau contrôleur</h3>
            <pre><code>&lt;?php
// src/controllers/blog.php

function index() {
    $data = [
        'title' => 'Mon Blog',
        'articles' => ['Article 1', 'Article 2']
    ];
    
    view('blog/index', $data);
}

function show($id) {
    $data = [
        'title' => 'Article #' . $id,
        'id' => $id
    ];
    
    view('blog/show', $data);
}</code></pre>
        </div>
        
        <div class="code-block">
            <h3>2. Créer la vue correspondante</h3>
            <pre><code>&lt;!-- src/views/blog/index.php --&gt;
&lt;?php partial('header', ['title' => $title]); ?&gt;

&lt;h1&gt;&lt;?= escape($title) ?&gt;&lt;/h1&gt;

&lt;?php foreach ($articles as $article): ?&gt;
    &lt;p&gt;&lt;?= escape($article) ?&gt;&lt;/p&gt;
&lt;?php endforeach; ?&gt;

&lt;?php partial('footer'); ?&gt;</code></pre>
        </div>
        
        <div class="code-block">
            <h3>3. Accéder via URL propre</h3>
            <pre><code>http://localhost/php-starter-pack/public/blog
http://localhost/php-starter-pack/public/blog/show/123</code></pre>
        </div>
    </div>
    
    <div class="cta">
        <h2>Prêt à coder ?</h2>
        <p>Consultez la documentation complète dans le fichier README.md</p>
        <div class="button-group">
            <a href="<?= url('home', 'about') ?>" class="btn btn-primary">En savoir plus</a>
            <a href="<?= url('home', 'contact') ?>" class="btn btn-secondary">Contactez-nous</a>
        </div>
    </div>
</div>

<?php partial('footer'); ?>
