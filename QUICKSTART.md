# ⚡ GUIDE DE DÉMARRAGE RAPIDE

## 🎯 Installation en 5 minutes

### 1️⃣ Configuration Apache (Virtual Host)

**Option A - Laragon (Recommandé)**

Laragon crée automatiquement un virtual host pour chaque dossier placé dans `www/`. Le projet est accessible via :

```
http://php-starter-pack.test/
```

Exemples d'URLs :

- Accueil : `http://php-starter-pack.test/`
- À propos : `http://php-starter-pack.test/home/about`
- Contact : `http://php-starter-pack.test/home/contact`

> Laragon génère le virtual host à partir du nom du dossier (`php-starter-pack`) et lui ajoute l'extension `.test`. Aucune configuration manuelle n'est nécessaire.

---

**Option B - Virtual Host manuel**

Ajouter dans votre fichier de configuration Apache (httpd-vhosts.conf ou sites-available) :

```apache
<VirtualHost *:80>
    ServerName monsite.local
    DocumentRoot "/chemin/vers/php-starter-pack/public"

    <Directory "/chemin/vers/php-starter-pack/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Ajouter dans `C:\Windows\System32\drivers\etc\hosts` (Windows) ou `/etc/hosts` (Linux/Mac) :

```
127.0.0.1   monsite.local
```

Redémarrer Apache et accéder à : **http://monsite.local**

---

**Option C - Sous-dossier localhost**

1. Placer le dossier dans `htdocs` ou `www`
2. Modifier `config/config.php` ligne 45 :

```php
define('BASE_URL', 'http://localhost/php-starter-pack/public');
```

3. Accéder à : **http://localhost/php-starter-pack/public**

> **Important — `RewriteBase` dans `public/.htaccess`** : la valeur doit correspondre à votre configuration.
>
> - Virtual host (Laragon `http://php-starter-pack.test`) → `RewriteBase /`
> - Sous-dossier (`http://localhost/php-starter-pack/public`) → `RewriteBase /php-starter-pack/public/`

---

### 2️⃣ Configuration Base de Données (Optionnel)

Si vous voulez utiliser la base de données :

1. Créer la base :

```bash
mysql -u root -p < database.sql
```

2. Configurer dans `config/config.php` (lignes 52-56) :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'php_starter');
define('DB_USER', 'root');
define('DB_PASS', 'votre_mot_de_passe');
```

---

### 3️⃣ Tester l'installation

Ouvrir votre navigateur :

- Page d'accueil : `http://php-starter-pack.test/`
- À propos : `http://php-starter-pack.test/home/about`
- Contact : `http://php-starter-pack.test/home/contact`

Si vous voyez la page d'accueil → **✅ Installation réussie !**

---

## 🚀 Premier pas : Créer une page "Blog"

### Étape 1 : Créer le contrôleur

Créer `src/controllers/blog.php` :

```php
<?php

function index() {
    $data = [
        'title' => 'Mon Blog',
        'articles' => [
            ['id' => 1, 'titre' => 'Premier article', 'contenu' => 'Lorem ipsum...'],
            ['id' => 2, 'titre' => 'Deuxième article', 'contenu' => 'Lorem ipsum...'],
            ['id' => 3, 'titre' => 'Troisième article', 'contenu' => 'Lorem ipsum...']
        ]
    ];

    view('blog/index', $data);
}

function show($id) {
    $articles = [
        1 => ['id' => 1, 'titre' => 'Premier article', 'contenu' => 'Contenu complet...'],
        2 => ['id' => 2, 'titre' => 'Deuxième article', 'contenu' => 'Contenu complet...'],
        3 => ['id' => 3, 'titre' => 'Troisième article', 'contenu' => 'Contenu complet...']
    ];

    if (!isset($articles[$id])) {
        redirect('error', 'notFound');
    }

    view('blog/show', ['article' => $articles[$id]]);
}
```

### Étape 2 : Créer les vues

Créer le dossier : `src/views/blog/`

**Fichier : `src/views/blog/index.php`**

```php
<?php partial('header', ['title' => $title]); ?>

<div class="container">
    <h1><?= escape($title) ?></h1>

    <div class="articles-list">
        <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <h2>
                    <a href="<?= url('blog', 'show', [$article['id']]) ?>">
                        <?= escape($article['titre']) ?>
                    </a>
                </h2>
                <p><?= escape(truncate($article['contenu'], 150)) ?></p>
                <a href="<?= url('blog', 'show', [$article['id']]) ?>" class="btn btn-primary">
                    Lire la suite
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<?php partial('footer'); ?>
```

**Fichier : `src/views/blog/show.php`**

```php
<?php partial('header', ['title' => $article['titre']]); ?>

<div class="container">
    <article>
        <h1><?= escape($article['titre']) ?></h1>
        <div class="content">
            <?= nl2br(escape($article['contenu'])) ?>
        </div>
        <a href="<?= url('blog') ?>" class="btn btn-secondary">← Retour au blog</a>
    </article>
</div>

<?php partial('footer'); ?>
```

### Étape 3 : Ajouter le style (optionnel)

Ajouter dans `public/css/style.css` :

```css
/* Articles */
.articles-list {
  display: grid;
  gap: 2rem;
  margin: 2rem 0;
}

.article-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.article-card h2 {
  margin-bottom: 1rem;
}

.article-card h2 a {
  color: var(--dark-color);
}

.article-card p {
  color: #666;
  margin-bottom: 1rem;
}
```

### Étape 4 : Tester

Accéder à :

- **Liste des articles** : `http://php-starter-pack.test/blog`
- **Article 1** : `http://php-starter-pack.test/blog/show/1`
- **Article 2** : `http://php-starter-pack.test/blog/show/2`

**✅ Félicitations ! Vous venez de créer votre premier module !**

---

## 🗄️ Utiliser la base de données

### Créer un modèle Article

**Fichier : `src/models/Article.php`**

```php
<?php

function getAllArticles() {
    $db = getDbConnection();
    $articles = dbQuery($db, "SELECT * FROM articles WHERE published = 1 ORDER BY created_at DESC");
    closeDbConnection($db);
    return $articles;
}

function getArticleById($id) {
    $db = getDbConnection();
    $article = dbQueryOne($db, "SELECT * FROM articles WHERE id = ? AND published = 1", [$id]);
    closeDbConnection($db);
    return $article;
}

function getArticleBySlug($slug) {
    $db = getDbConnection();
    $article = dbQueryOne($db, "SELECT * FROM articles WHERE slug = ? AND published = 1", [$slug]);
    closeDbConnection($db);
    return $article;
}

function createArticle($data) {
    $db = getDbConnection();

    $slug = slugify($data['title']);

    $id = dbExecute($db,
        "INSERT INTO articles (user_id, title, slug, content, excerpt, published) VALUES (?, ?, ?, ?, ?, ?)",
        [$data['user_id'], $data['title'], $slug, $data['content'], $data['excerpt'], $data['published']]
    );

    closeDbConnection($db);
    return $id;
}
```

### Modifier le contrôleur pour utiliser la BDD

**Fichier : `src/controllers/blog.php`**

```php
<?php

// Charger le modèle
require_once MODELS_PATH . '/Article.php';

function index() {
    // Récupérer depuis la BDD
    $articles = getAllArticles();

    $data = [
        'title' => 'Mon Blog',
        'articles' => $articles
    ];

    view('blog/index', $data);
}

function show($id) {
    // Vérifier que l'ID est numérique
    if (!is_numeric($id)) {
        redirect('error', 'notFound');
    }

    // Récupérer depuis la BDD
    $article = getArticleById($id);

    if (!$article) {
        redirect('error', 'notFound');
    }

    // Incrémenter les vues (optionnel)
    $db = getDbConnection();
    dbExecute($db, "UPDATE articles SET views = views + 1 WHERE id = ?", [$id]);
    closeDbConnection($db);

    view('blog/show', ['article' => $article]);
}
```

---

## 🔐 Ajouter la sécurité CSRF

### Dans le formulaire de contact

**Fichier : `src/views/home/contact.php`** (déjà fait ✅)

```php
<form method="POST" action="<?= url('home', 'contact') ?>">
    <!-- Token CSRF -->
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

    <!-- Champs du formulaire -->
    ...
</form>
```

### Dans le contrôleur

**Fichier : `src/controllers/home.php`** (ajouter la vérification)

```php
function contact() {
    if (isMethod('POST')) {
        // Vérifier le token CSRF
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlashMessage('error', 'Token de sécurité invalide.');
            redirect('home', 'contact');
        }

        // Suite du traitement...
    }
}
```

---

## 📝 Checklist de vérification

- [ ] Apache configuré et redémarré
- [ ] Page d'accueil accessible
- [ ] URLs propres fonctionnent (`/home/about`)
- [ ] Base de données créée (si nécessaire)
- [ ] Module blog créé et fonctionnel
- [ ] Messages flash affichés
- [ ] Formulaire de contact fonctionne
- [ ] Protection CSRF active

---

## 🆘 Problèmes courants

### ❌ Erreur 404 sur toutes les pages

**Cause :** mod_rewrite non activé

**Solution :**

```bash
# Linux
sudo a2enmod rewrite
sudo systemctl restart apache2

# Windows/XAMPP
Vérifier dans httpd.conf que cette ligne n'est pas commentée :
LoadModule rewrite_module modules/mod_rewrite.so
```

---

### ❌ Erreur "The requested URL was not found"

**Cause :** BASE_URL incorrect

**Solution :** Vérifier `config/config.php` ligne 45

---

### ❌ CSS/JS ne se chargent pas

**Cause :** Chemins incorrects

**Solution :** Vérifier les constantes CSS_URL et JS_URL dans `config/config.php`

---

### ❌ Erreur de connexion à la BDD

**Cause :** Credentials incorrects

**Solution :** Vérifier DB_HOST, DB_USER, DB_PASS dans `config/config.php`

---

## 🎓 Prochaines étapes

1. ✅ Maîtriser le routage et la création de pages
2. ✅ Créer des modèles pour vos données
3. ✅ Implémenter un système d'authentification
4. ✅ Ajouter un système de pagination
5. ✅ Créer un backoffice d'administration
6. ✅ Implémenter l'upload de fichiers
7. ✅ Ajouter des validations avancées

---

**🎉 Vous êtes prêt à développer votre application PHP !**

Pour plus de détails, consultez :

- `README.md` : Documentation complète
- `ARCHITECTURE.md` : Comprendre l'architecture
