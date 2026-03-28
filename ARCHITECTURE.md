# 📐 ARCHITECTURE DU PROJET

## Vue d'ensemble

Ce projet suit une architecture **MVC-like procédurale** avec un **Front Controller Pattern**. Bien qu'écrit en procédural, il respecte les principes de séparation des responsabilités et d'organisation du code.

---

## 🎯 Principes architecturaux

### 1. Séparation des responsabilités

```
┌─────────────────────────────────────────────────────┐
│                   UTILISATEUR                       │
└──────────────────────┬──────────────────────────────┘
                       │ Requête HTTP
                       ▼
┌─────────────────────────────────────────────────────┐
│              FRONT CONTROLLER (index.php)           │
│  - Point d'entrée unique                            │
│  - Charge la configuration                          │
│  - Démarre la session                               │
│  - Gère les erreurs                                 │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────┐
│                   ROUTEUR                           │
│  - Parse l'URL                                      │
│  - Identifie contrôleur/action                      │
│  - Distribue la requête                             │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────┐
│                 CONTRÔLEUR                          │
│  - Traite la logique métier                         │
│  - Valide les données                               │
│  - Interagit avec les modèles                       │
│  - Prépare les données pour la vue                  │
└──────────────────────┬──────────────────────────────┘
                       │
        ┌──────────────┴──────────────┐
        ▼                             ▼
┌──────────────┐            ┌──────────────────┐
│   MODÈLE     │            │      VUE         │
│              │            │                  │
│ - Accès BDD  │            │ - Affichage HTML │
│ - Logique    │◄───────────│ - Templates      │
│   données    │   Données  │ - Partials       │
└──────────────┘            └──────────────────┘
```

---

## 📂 Organisation des fichiers

### Structure détaillée

```
php-starter-pack/
│
├── config/                      # CONFIGURATION
│   └── config.php              # Config centralisée (chemins, BDD, etc.)
│
├── logs/                        # LOGS
│   ├── app.log                 # Logs applicatifs
│   └── php-errors.log          # Logs erreurs PHP
│
├── public/                      # DOSSIER PUBLIC (accessible web)
│   │
│   ├── css/                    # Styles CSS
│   │   └── style.css
│   │
│   ├── js/                     # JavaScript
│   │   └── main.js
│   │
│   ├── images/                 # Images statiques
│   │
│   ├── uploads/                # Fichiers uploadés (à créer)
│   │
│   ├── .htaccess               # Réécriture d'URL Apache
│   │
│   └── index.php               # ★ FRONT CONTROLLER (point d'entrée)
│
└── src/                         # CODE SOURCE
    │
    ├── controllers/             # CONTRÔLEURS (logique métier)
    │   ├── home.php            # Contrôleur page d'accueil
    │   ├── error.php           # Contrôleur erreurs
    │   └── (autres...)         # Vos contrôleurs
    │
    ├── views/                   # VUES (templates HTML)
    │   ├── home/
    │   │   ├── index.php
    │   │   ├── about.php
    │   │   └── contact.php
    │   ├── error/
    │   │   └── 404.php
    │   └── partials/
    │       ├── header.php       # En-tête réutilisable
    │       └── footer.php       # Pied de page réutilisable
    │
    ├── models/                  # MODÈLES (logique données)
    │   ├── User.php            # Modèle utilisateur
    │   └── (autres...)         # Vos modèles
    │
    └── helpers/                 # FONCTIONS UTILITAIRES
        ├── router.php          # Système de routage
        ├── functions.php       # Fonctions générales
        └── database.php        # Fonctions BDD
```

---

## 🔄 Flux de traitement d'une requête

### Exemple concret : `/blog/show/5`

```
1. REQUÊTE HTTP
   └─> http://monsite.local/blog/show/5

2. .htaccess (Apache)
   └─> Réécriture : index.php?url=blog/show/5

3. FRONT CONTROLLER (public/index.php)
   ├─> Charge config.php
   ├─> Charge les helpers
   ├─> Démarre la session
   ├─> Configure la gestion d'erreurs
   └─> Appelle dispatch()

4. ROUTEUR (src/helpers/router.php)
   ├─> parseUrl() analyse "blog/show/5"
   │   └─> controller = 'blog'
   │   └─> action = 'show'
   │   └─> params = [5]
   │
   ├─> Vérifie que blog.php existe
   ├─> Charge blog.php
   ├─> Vérifie que la fonction show() existe
   └─> Exécute show(5)

5. CONTRÔLEUR (src/controllers/blog.php)
   ├─> function show($id) {
   ├─> Charge le modèle Article.php
   ├─> Récupère l'article depuis la BDD
   ├─> Prépare les données
   └─> view('blog/show', $data)
   │
   └─> Si article introuvable : redirect('error', 'notFound')

6. VUE (src/views/blog/show.php)
   ├─> partial('header')
   ├─> Affiche le contenu HTML
   │   └─> Utilise les données : <?= escape($article['title']) ?>
   └─> partial('footer')

7. RÉPONSE HTTP
   └─> HTML final envoyé au navigateur
```

---

## 🧩 Rôles de chaque composant

### Front Controller (`public/index.php`)

**Responsabilités :**
- Point d'entrée unique de l'application
- Charge la configuration globale
- Initialise la session
- Configure la gestion d'erreurs
- Lance le routeur

**Ne fait PAS :**
- Logique métier
- Accès base de données
- Affichage HTML

---

### Routeur (`src/helpers/router.php`)

**Responsabilités :**
- Parser les URLs propres
- Identifier le contrôleur et l'action
- Vérifier l'existence des fichiers
- Distribuer la requête
- Gérer les erreurs 404

**Fonctions principales :**
```php
parseUrl()           // Parse l'URL
dispatch()           // Distribue la requête
url()                // Génère une URL
redirect()           // Redirige vers une autre page
```

---

### Contrôleurs (`src/controllers/`)

**Responsabilités :**
- Traiter la logique métier
- Valider les données entrantes
- Appeler les modèles pour récupérer/modifier des données
- Préparer les données pour les vues
- Charger les vues appropriées

**Structure type :**
```php
<?php
// src/controllers/blog.php

function index() {
    // 1. Récupérer les données (via modèles)
    require_once MODELS_PATH . '/Article.php';
    $articles = getAllArticles();
    
    // 2. Préparer les données
    $data = [
        'title' => 'Blog',
        'articles' => $articles
    ];
    
    // 3. Charger la vue
    view('blog/index', $data);
}

function show($id) {
    // Validation
    if (!is_numeric($id)) {
        redirect('error', 'notFound');
    }
    
    // Récupération
    require_once MODELS_PATH . '/Article.php';
    $article = getArticleById($id);
    
    if (!$article) {
        redirect('error', 'notFound');
    }
    
    // Vue
    view('blog/show', ['article' => $article]);
}

function create() {
    // Formulaire POST
    if (isMethod('POST')) {
        // Validation
        $errors = validate($_POST, [
            'title' => ['required', 'min:3'],
            'content' => ['required', 'min:10']
        ]);
        
        if (empty($errors)) {
            // Création
            require_once MODELS_PATH . '/Article.php';
            $id = createArticle($_POST);
            
            // Message + redirection
            setFlashMessage('success', 'Article créé !');
            redirect('blog', 'show', [$id]);
        }
    }
    
    // Affichage formulaire
    view('blog/create', ['errors' => $errors ?? []]);
}
```

**Ne fait PAS :**
- Requêtes SQL directes (utilise les modèles)
- HTML (utilise les vues)
- Echo/print de contenu

---

### Modèles (`src/models/`)

**Responsabilités :**
- Accès à la base de données
- Logique de manipulation des données
- Validation métier complexe
- Encapsulation des requêtes SQL

**Structure type :**
```php
<?php
// src/models/Article.php

function getAllArticles() {
    $db = getDbConnection();
    $articles = dbQuery($db, "SELECT * FROM articles ORDER BY created_at DESC");
    closeDbConnection($db);
    return $articles;
}

function getArticleById($id) {
    $db = getDbConnection();
    $article = dbQueryOne($db, "SELECT * FROM articles WHERE id = ?", [$id], 'i');
    closeDbConnection($db);
    return $article;
}

function createArticle($data) {
    $db = getDbConnection();
    $id = dbExecute($db, 
        "INSERT INTO articles (title, content, user_id) VALUES (?, ?, ?)",
        [$data['title'], $data['content'], $_SESSION['user_id']],
        'ssi'
    );
    closeDbConnection($db);
    return $id;
}
```

**Règles :**
- Une fonction = une opération
- Toujours utiliser des requêtes préparées
- Ouvrir/fermer la connexion dans chaque fonction
- Retourner des données, pas d'affichage

---

### Vues (`src/views/`)

**Responsabilités :**
- Affichage HTML
- Utilisation des données fournies
- Échappement des sorties
- Inclusion des partials

**Structure type :**
```php
<?php 
// src/views/blog/show.php
partial('header', ['title' => $article['title']]); 
?>

<div class="container">
    <article>
        <h1><?= escape($article['title']) ?></h1>
        <p class="meta">
            Publié le <?= formatDate($article['created_at']) ?>
            par <?= escape($article['author']) ?>
        </p>
        <div class="content">
            <?= nl2br(escape($article['content'])) ?>
        </div>
    </article>
    
    <a href="<?= url('blog') ?>" class="btn">Retour</a>
</div>

<?php partial('footer'); ?>
```

**Règles :**
- TOUJOURS échapper avec `escape()`
- Pas de logique complexe
- Pas d'accès direct à la BDD
- Utiliser les helpers pour le formatage

---

### Helpers (`src/helpers/`)

**Responsabilités :**
- Fonctions utilitaires réutilisables
- Abstractions communes
- Sécurité (XSS, CSRF)
- Validation
- Formatage

**Catégories :**

1. **router.php** : Routage
2. **functions.php** : Fonctions générales (sécurité, validation, vues)
3. **database.php** : Fonctions BDD

---

## 🔐 Sécurité dans l'architecture

### Niveaux de protection

```
1. .htaccess
   └─> Bloque l'accès aux fichiers sensibles (.env, .log)

2. Front Controller
   └─> Valide les requêtes
   └─> Configure les sessions sécurisées

3. Routeur
   └─> Nettoie les URLs (FILTER_SANITIZE_URL)
   └─> Vérifie l'existence des fichiers

4. Contrôleurs
   └─> Validation des données (validate())
   └─> Vérification CSRF
   └─> Nettoyage des entrées (clean())

5. Modèles
   └─> Requêtes préparées OBLIGATOIRES
   └─> Pas d'échappement manuel

6. Vues
   └─> Échappement HTML (escape())
   └─> Protection XSS
```

---

## 🚀 Extension de l'architecture

### Ajouter un nouveau module (exemple : Blog)

#### 1. Créer le contrôleur
```bash
touch src/controllers/blog.php
```

#### 2. Créer le modèle
```bash
touch src/models/Article.php
```

#### 3. Créer les vues
```bash
mkdir src/views/blog
touch src/views/blog/index.php
touch src/views/blog/show.php
touch src/views/blog/create.php
```

#### 4. URLs automatiques
```
/blog           → blog.php :: index()
/blog/show/5    → blog.php :: show(5)
/blog/create    → blog.php :: create()
```

---

## 💡 Bonnes pratiques architecturales

### ✅ À FAIRE

1. **Un fichier de contrôleur = un domaine métier**
   - `users.php` pour les utilisateurs
   - `blog.php` pour les articles
   - `products.php` pour les produits

2. **Une fonction = une action**
   - `index()` → liste
   - `show($id)` → détails
   - `create()` → création
   - `update($id)` → modification
   - `delete($id)` → suppression

3. **Modèles = source unique de vérité**
   - Toute la logique données dans les modèles
   - Réutilisable entre contrôleurs

4. **Vues = présentation uniquement**
   - Pas de logique métier
   - Juste de l'affichage

5. **Helpers = DRY (Don't Repeat Yourself)**
   - Code répété → helper

### ❌ À ÉVITER

1. SQL direct dans les contrôleurs
2. Logique métier dans les vues
3. Echo/print dans les modèles
4. Variables globales
5. Code dupliqué

---

## 📊 Comparaison Procédural vs POO

| Aspect | Procédural (ce projet) | POO |
|--------|------------------------|-----|
| Organisation | Fichiers + fonctions | Classes + méthodes |
| Contrôleur | `function index()` | `class BlogController { public function index() }` |
| Modèle | `function getUser($id)` | `class User { public function find($id) }` |
| Namespace | Fichiers séparés | `namespace App\Controllers` |
| Autoloading | `require_once` | Autoloader PSR-4 |
| Dépendances | Fonctions globales | Injection de dépendances |

**Point clé :** Cette architecture procédurale facilite la **transition vers l'OOP** car les concepts (MVC, séparation) sont identiques.

---

## 🎓 Évolution recommandée

1. **Niveau 1** : Maîtriser cette architecture procédurale
2. **Niveau 2** : Ajouter un autoloader simple
3. **Niveau 3** : Convertir progressivement en classes
4. **Niveau 4** : Utiliser un framework (Laravel, Symfony)

---

Cette architecture vous donne des **bases solides** pour comprendre les frameworks modernes tout en restant dans le procédural pur.
