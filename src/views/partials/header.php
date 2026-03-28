<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= isset($description) ? escape($description) : 'PHP Starter Pack' ?>">
    <title><?= isset($title) ? escape($title) . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    
    <!-- Favicon (optionnel) -->
    <!-- <link rel="icon" type="image/x-icon" href="<?= IMAGES_URL ?>/favicon.ico"> -->
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="<?= url() ?>" class="logo"><?= APP_NAME ?></a>
            <ul class="nav-links">
                <li><a href="<?= url() ?>">Accueil</a></li>
                <li><a href="<?= url('home', 'about') ?>">À propos</a></li>
                <li><a href="<?= url('home', 'contact') ?>">Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Messages Flash -->
    <?php if (hasFlashMessage('success')): ?>
        <div class="alert alert-success">
            <?= escape(getFlashMessage('success')) ?>
        </div>
    <?php endif; ?>
    
    <?php if (hasFlashMessage('error')): ?>
        <div class="alert alert-error">
            <?= escape(getFlashMessage('error')) ?>
        </div>
    <?php endif; ?>
    
    <?php if (hasFlashMessage('warning')): ?>
        <div class="alert alert-warning">
            <?= escape(getFlashMessage('warning')) ?>
        </div>
    <?php endif; ?>
    
    <?php if (hasFlashMessage('info')): ?>
        <div class="alert alert-info">
            <?= escape(getFlashMessage('info')) ?>
        </div>
    <?php endif; ?>
    
    <!-- Contenu principal -->
    <main class="main-content">
