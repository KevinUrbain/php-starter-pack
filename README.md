# 🚀 PHP STARTER PACK - Framework Procédural Professionnel

Un starter pack complet et professionnel pour démarrer rapidement vos projets PHP procéduraux avec une architecture propre, des URLs propres et des bonnes pratiques.

## 📋 Table des matières

1. [Fonctionnalités](#fonctionnalités)
2. [Prérequis](#prérequis)
3. [Installation](#installation)
4. [Structure du projet](#structure-du-projet)
5. [Configuration](#configuration)
6. [Utilisation](#utilisation)
7. [Routage](#routage)
8. [Contrôleurs](#contrôleurs)
9. [Vues](#vues)
10. [Base de données](#base-de-données)
11. [Helpers](#helpers)
12. [Sécurité](#sécurité)
13. [Bonnes pratiques](#bonnes-pratiques)

---

## ✨ Fonctionnalités

- ✅ **Front Controller** unique (index.php)
- ✅ **Routeur procédural puissant** avec URLs propres
- ✅ **Architecture MVC-like** (séparation des responsabilités)
- ✅ **Système de templates** avec partials (header/footer)
- ✅ **Helpers utiles** (validation, sécurité, vues, etc.)
- ✅ **Gestion des sessions** et messages flash
- ✅ **Sécurité intégrée** (XSS, CSRF, SQL injection)
- ✅ **Base de données MySQL** avec requêtes préparées
- ✅ **Gestion des erreurs** personnalisée
- ✅ **Logs** d'application
- ✅ **Configuration centralisée**
- ✅ **Support multi-environnement** (dev/prod)

---

## 🔧 Prérequis

- PHP 7.4 ou supérieur
- Apache avec mod_rewrite activé
- MySQL/MariaDB (optionnel)

---

## 📦 Installation

### 1. Cloner ou télécharger le projet

```bash
git clone https://github.com/votre-repo/php-starter-pack.git
cd php-starter-pack
```

### 2. Configurer Apache

**Option A : Virtual Host (recommandé)**

Créer un virtual host dans votre configuration Apache :

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

N'oubliez pas d'ajouter `monsite.local` dans votre fichier `hosts` :

```
127.0.0.1   monsite.local
```

**Option B : Sous-dossier localhost**

Placer le projet dans votre dossier `htdocs` ou `www` et ajuster `BASE_URL` dans `config/config.php`.

### 3. Configurer les permissions

```bash
chmod -R 755 php-starter-pack
chmod -R 777 logs/  # Optionnel : pour les logs
```

### 4. Configurer l'application

Éditer `config/config.php` et ajuster :

```php
// URL de base
define('BASE_URL', 'http://monsite.local'); // ou http://localhost/php-starter-pack/public

// Base de données (si nécessaire)
define('DB_HOST', 'localhost');
define('DB_NAME', 'nom_de_votre_base');
define('DB_USER', 'votre_user');
define('DB_PASS', 'votre_password');

// Clé secrète (IMPORTANT EN PRODUCTION)
define('SECRET_KEY', 'changez-cette-cle-en-production');
```

### 5. Créer la base de données (optionnel)

```sql
CREATE DATABASE php_starter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Tester l'installation

Ouvrir votre navigateur et accéder à :

- `http://monsite.local` ou `http://monsite.test`(virtual host)
- `http://localhost/php-starter-pack/public` (sous-dossier)

---

## 📁 Structure du projet

```
php-starter-pack/
├── config/
│   └── config.php              # Configuration principale
│
├── logs/
│   ├── app.log                 # Logs applicatifs
│   └── php-errors.log          # Logs d'erreurs PHP
│
├── public/                     # Dossier racine accessible par le web
│   ├── css/
│   │   └── style.css          # Styles CSS
│   ├── js/
│   │   └── main.js            # JavaScript
│   ├── images/                # Images
│   ├── .htaccess              # Réécriture d'URL
│   └── index.php              # Front Controller (point d'entrée)
│
└── src/
    ├── controllers/            # Contrôleurs (logique métier)
    │   ├── home.php
    │   └── error.php
    │
    ├── views/                  # Vues (templates)
    │   ├── home/
    │   │   ├── index.php
    │   │   ├── about.php
    │   │   └── contact.php
    │   ├── error/
    │   │   └── 404.php
    │   └── partials/
    │       ├── header.php
    │       └── footer.php
    │
    ├── models/                 # Modèles (accès données)
    │   └── (vos modèles)
    │
    └── helpers/                # Fonctions utilitaires
        ├── router.php          # Système de routage
        ├── functions.php       # Fonctions générales
        └── database.php        # Fonctions BDD
```

---

## ⚙️ Configuration

Le fichier `config/config.php` contient toute la configuration :

### Chemins

```php
ROOT_PATH           // Racine du projet
PUBLIC_PATH         // Dossier public
SRC_PATH            // Dossier src
CONTROLLERS_PATH    // Contrôleurs
VIEWS_PATH          // Vues
MODELS_PATH         // Modèles
HELPERS_PATH        // Helpers
LOGS_PATH           // Logs
```

### URLs

```php
BASE_URL            // URL de base du site
CSS_URL             // URL du CSS
JS_URL              // URL du JavaScript
IMAGES_URL          // URL des images
```

### Environnement

```php
ENVIRONMENT         // 'development' ou 'production'
```

---

## 🎯 Utilisation

### Créer une nouvelle page

#### 1. Créer le contrôleur

`src/controllers/blog.php` :

```php
<?php

function index() {
    $data = [
        'title' => 'Mon Blog',
        'articles' => [
            ['id' => 1, 'titre' => 'Article 1'],
            ['id' => 2, 'titre' => 'Article 2']
        ]
    ];

    view('blog/index', $data);
}

function show($id) {
    // Récupérer l'article depuis la BDD
    $db = getDbConnection();
    $article = dbQueryOne($db, "SELECT * FROM articles WHERE id = ?", [$id]);
    closeDbConnection($db);

    if (!$article) {
        redirect('error', 'notFound');
    }

    $data = [
        'title' => $article['titre'],
        'article' => $article
    ];

    view('blog/show', $data);
}
```

#### 2. Créer la vue

`src/views/blog/index.php` :

```php
<?php partial('header', ['title' => $title]); ?>

<div class="container">
    <h1><?= escape($title) ?></h1>

    <?php foreach ($articles as $article): ?>
        <article>
            <h2>
                <a href="<?= url('blog', 'show', [$article['id']]) ?>">
                    <?= escape($article['titre']) ?>
                </a>
            </h2>
        </article>
    <?php endforeach; ?>
</div>

<?php partial('footer'); ?>
```

#### 3. Accéder à la page

```
http://monsite.local/blog
http://monsite.local/blog/show/1
```

---

## 🛣️ Routage

### URLs propres

Le routeur convertit automatiquement les URLs en contrôleurs et actions :

```
/controller/action/param1/param2
```

**Exemples :**

| URL                   | Contrôleur | Action  | Paramètres |
| --------------------- | ---------- | ------- | ---------- |
| `/`                   | home       | index   | []         |
| `/home/about`         | home       | about   | []         |
| `/blog`               | blog       | index   | []         |
| `/blog/show/5`        | blog       | show    | [5]        |
| `/users/profile/john` | users      | profile | ['john']   |

### Fonctions de routage

#### Générer une URL

```php
url('blog', 'show', [5]);
// Retourne : http://monsite.local/blog/show/5
```

#### Rediriger

```php
redirect('home', 'index');
redirect('blog', 'show', [5]);
```

#### Vérifier la méthode HTTP

```php
if (isMethod('POST')) {
    // Traitement du formulaire
}
```

### Routes personnalisées (avancé)

Dans `public/index.php` :

```php
// Avant dispatch()
addRoute('blog/article/:id', 'blog', 'show');
addRoute('user/:username', 'users', 'profile');
```

---

## 🎮 Contrôleurs

Les contrôleurs contiennent la logique métier. Chaque fonction = une action.

### Structure d'un contrôleur

```php
<?php
// src/controllers/products.php

function index() {
    // Liste des produits
    $db = getDbConnection();
    $products = dbQuery($db, "SELECT * FROM products");
    closeDbConnection($db);

    view('products/index', ['products' => $products]);
}

function show($id) {
    // Détails d'un produit
    $db = getDbConnection();
    $product = dbQueryOne($db, "SELECT * FROM products WHERE id = ?", [$id]);
    closeDbConnection($db);

    if (!$product) {
        redirect('error', 'notFound');
    }

    view('products/show', ['product' => $product]);
}

function create() {
    // Afficher le formulaire
    if (isMethod('POST')) {
        // Traiter le formulaire
        $errors = validate($_POST, [
            'name' => ['required', 'min:3'],
            'price' => ['required']
        ]);

        if (empty($errors)) {
            $db = getDbConnection();
            $id = dbExecute($db,
                "INSERT INTO products (name, price) VALUES (?, ?)",
                [$_POST['name'], $_POST['price']]
            );
            closeDbConnection($db);

            setFlashMessage('success', 'Produit créé avec succès !');
            redirect('products', 'show', [$id]);
        }
    }

    view('products/create');
}
```

---

## 👁️ Vues

### Charger une vue

```php
view('nom/de/la/vue', $data);
```

### Utiliser les partials

```php
partial('header', ['title' => 'Mon titre']);
partial('footer');
```

### Accéder aux données

Les données passées deviennent des variables :

```php
// Dans le contrôleur
view('blog/index', ['articles' => $articles, 'title' => 'Blog']);

// Dans la vue
<?= escape($title) ?>
<?php foreach ($articles as $article): ?>
    <?= escape($article['titre']) ?>
<?php endforeach; ?>
```

---

## 🗄️ Base de données

### Connexion

```php
$db = getDbConnection();
// ... requêtes
closeDbConnection($db);
```

### Requêtes SELECT

```php
// Plusieurs résultats
$users = dbQuery($db, "SELECT * FROM users WHERE active = ?", [1]);

// Un seul résultat
$user = dbQueryOne($db, "SELECT * FROM users WHERE id = ?", [$id]);
```

### INSERT, UPDATE, DELETE

```php
// INSERT
$id = dbExecute($db,
    "INSERT INTO users (name, email) VALUES (?, ?)",
    ['John', 'john@example.com']
);

// UPDATE
$success = dbExecute($db,
    "UPDATE users SET name = ? WHERE id = ?",
    ['Jane', 5]
);

// DELETE
$success = dbExecute($db,
    "DELETE FROM users WHERE id = ?",
    [5]
);
```

---

## 🛠️ Helpers

### Sécurité

```php
escape($string);                    // Échappe HTML (XSS)
clean($string);                     // Nettoie (strip_tags + trim)
isValidEmail($email);               // Valide un email
generateCsrfToken();                // Génère un token CSRF
verifyCsrfToken($token);           // Vérifie le token CSRF
```

### Sessions et Flash

```php
startSession();                     // Démarre la session
setFlashMessage('success', 'OK!');  // Message flash
getFlashMessage('success');         // Récupère le message
hasFlashMessage('success');         // Vérifie l'existence
```

### Validation

```php
$errors = validate($_POST, [
    'email' => ['required', 'email'],
    'password' => ['required', 'min:8'],
    'name' => ['required', 'min:3', 'max:50']
]);
```

### Utilitaires

```php
formatDate($date, 'd/m/Y');        // Formate une date
truncate($text, 100);               // Tronque un texte
slugify($string);                   // Crée un slug
isLoggedIn();                       // Vérifie si connecté
currentUser();                      // Récupère l'utilisateur
logMessage($msg, 'error');          // Log un message
```

### Debug

```php
dd($data);                          // Dump et die
dump($data);                        // Dump sans die
```

---

## 🔒 Sécurité

### Protection XSS

**Toujours** échapper les sorties :

```php
<?= escape($userInput) ?>
```

### Protection CSRF

Dans les formulaires :

```php
<input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
```

Vérification :

```php
if (!verifyCsrfToken($_POST['csrf_token'])) {
    die('Token CSRF invalide');
}
```

### Protection SQL Injection

**Toujours** utiliser les requêtes préparées :

```php
// ✅ BIEN
dbQuery($db, "SELECT * FROM users WHERE id = ?", [$id]);

// ❌ MAL
$db->query("SELECT * FROM users WHERE id = $id");
```

### Validation des données

```php
$errors = validate($_POST, [
    'email' => ['required', 'email'],
    'age' => ['required', 'min:1', 'max:150']
]);

if (!empty($errors)) {
    // Afficher les erreurs
}
```

---

## 📝 Bonnes pratiques

### 1. Toujours échapper les sorties

```php
<?= escape($variable) ?>
```

### 2. Utiliser les requêtes préparées

```php
dbQuery($db, "SELECT * FROM table WHERE col = ?", [$value]);
```

### 3. Valider les entrées utilisateur

```php
$errors = validate($_POST, $rules);
```

### 4. Utiliser les messages flash

```php
setFlashMessage('success', 'Opération réussie !');
redirect('home', 'index');
```

### 5. Séparer la logique de la présentation

- Logique → Contrôleurs
- Affichage → Vues
- Données → Modèles

### 6. Ne jamais exposer les erreurs en production

```php
// Dans config.php
define('ENVIRONMENT', 'production');
```

### 7. Logger les erreurs

```php
logMessage('Erreur critique', 'error');
```

### 8. Utiliser des constantes pour les chemins

```php
require_once HELPERS_PATH . '/functions.php';
```

---

## 🚀 Aller plus loin

### Créer un modèle réutilisable

`src/models/User.php` :

```php
<?php

function getAllUsers() {
    $db = getDbConnection();
    $users = dbQuery($db, "SELECT * FROM users");
    closeDbConnection($db);
    return $users;
}

function getUserById($id) {
    $db = getDbConnection();
    $user = dbQueryOne($db, "SELECT * FROM users WHERE id = ?", [$id]);
    closeDbConnection($db);
    return $user;
}

function createUser($data) {
    $db = getDbConnection();
    $id = dbExecute($db,
        "INSERT INTO users (name, email, password) VALUES (?, ?, ?)",
        [$data['name'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT)]
    );
    closeDbConnection($db);
    return $id;
}
```

Utilisation dans le contrôleur :

```php
require_once MODELS_PATH . '/User.php';

function index() {
    $users = getAllUsers();
    view('users/index', ['users' => $users]);
}
```

---

## 📞 Support

Pour toute question ou problème :

- Email : contact@monsite.com
- Issues : https://github.com/votre-repo/issues

---

## 📄 Licence

MIT License - Libre d'utilisation

---

## ✅ Checklist de démarrage

- [ ] Installation et configuration
- [ ] Tester la page d'accueil
- [ ] Créer votre premier contrôleur
- [ ] Créer votre première vue
- [ ] Configurer la base de données
- [ ] Tester les URLs propres
- [ ] Implémenter un formulaire avec validation
- [ ] Ajouter des messages flash
- [ ] Sécuriser avec CSRF
- [ ] Déployer en production

---

**Bon développement ! 🎉**
# php-starter-pack
