<?php partial('header', ['title' => $title]); ?>

<div class="container">
    <h1><?= escape($title) ?></h1>
    
    <div class="contact-intro">
        <p>Vous avez une question ? N'hésitez pas à nous contacter via le formulaire ci-dessous.</p>
    </div>
    
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Erreurs dans le formulaire :</strong>
            <ul>
                <?php foreach ($errors as $field => $fieldErrors): ?>
                    <?php foreach ($fieldErrors as $error): ?>
                        <li><?= escape($error) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= url('home', 'contact') ?>" class="contact-form">
        <!-- Token CSRF -->
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        
        <div class="form-group">
            <label for="name">Nom *</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="<?= isset($old['name']) ? escape($old['name']) : '' ?>"
                required
            >
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?= isset($old['email']) ? escape($old['email']) : '' ?>"
                required
            >
        </div>
        
        <div class="form-group">
            <label for="message">Message *</label>
            <textarea 
                id="message" 
                name="message" 
                rows="6" 
                required
            ><?= isset($old['message']) ? escape($old['message']) : '' ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    
    <div class="contact-info">
        <h2>Informations de contact</h2>
        <p><strong>Email :</strong> <?= CONTACT_EMAIL ?></p>
        <p><strong>Adresse :</strong> Brussels, Belgique</p>
    </div>
</div>

<?php partial('footer'); ?>
