    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?> - Version <?= APP_VERSION ?></p>
            <p>
                <a href="<?= url() ?>">Accueil</a> | 
                <a href="<?= url('home', 'about') ?>">À propos</a> | 
                <a href="<?= url('home', 'contact') ?>">Contact</a>
            </p>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="<?= JS_URL ?>/main.js"></script>
</body>
</html>
