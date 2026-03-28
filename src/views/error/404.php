<?php partial('header', ['title' => $title]); ?>

<div class="container">
    <div class="error-page">
        <div class="error-code"><?= $code ?></div>
        <h1><?= escape($title) ?></h1>
        <p class="error-message"><?= escape($message) ?></p>
        
        <div class="error-actions">
            <a href="<?= url() ?>" class="btn btn-primary">Retour à l'accueil</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Page précédente</a>
        </div>
        
        <?php if (ENVIRONMENT === 'development'): ?>
            <div class="debug-info">
                <h3>Informations de debug :</h3>
                <p><strong>URL demandée :</strong> <?= escape($_SERVER['REQUEST_URI']) ?></p>
                <p><strong>Méthode :</strong> <?= escape($_SERVER['REQUEST_METHOD']) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php partial('footer'); ?>
