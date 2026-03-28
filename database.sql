-- =====================================================
-- SCRIPT SQL - BASE DE DONNÉES EXEMPLE
-- =====================================================
-- Ce fichier crée les tables nécessaires pour démarrer
-- Exécutez ce script dans votre base de données

-- =====================================================
-- CRÉATION DE LA BASE
-- =====================================================
CREATE DATABASE IF NOT EXISTS php_starter 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE php_starter;

-- =====================================================
-- TABLE USERS
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE ARTICLES (EXEMPLE BLOG)
-- =====================================================
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt VARCHAR(500),
    published TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_published (published),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE CATEGORIES (EXEMPLE)
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE ARTICLE_CATEGORIES (RELATION MANY-TO-MANY)
-- =====================================================
CREATE TABLE IF NOT EXISTS article_categories (
    article_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (article_id, category_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE COMMENTS (EXEMPLE)
-- =====================================================
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_id INT,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_article_id (article_id),
    INDEX idx_approved (approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE SESSIONS (OPTIONNEL - STOCKAGE EN BDD)
-- =====================================================
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    data TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DONNÉES DE TEST
-- =====================================================

-- Utilisateur de test (mot de passe: password123)
INSERT INTO users (name, email, password) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Catégories
INSERT INTO categories (name, slug, description) VALUES
('Technologie', 'technologie', 'Articles sur la technologie'),
('Développement Web', 'developpement-web', 'Tutoriels et articles sur le dev web'),
('PHP', 'php', 'Tout sur PHP'),
('Actualités', 'actualites', 'Les dernières nouvelles');

-- Articles
INSERT INTO articles (user_id, title, slug, content, excerpt, published, views) VALUES
(1, 'Introduction au PHP procédural', 'introduction-php-procedural', 
 'Le PHP procédural est une approche de programmation où le code est organisé en fonctions...',
 'Découvrez les bases du PHP procédural',
 1, 150),
(1, 'Créer un système de routage en PHP', 'systeme-routage-php',
 'Un bon système de routage est essentiel pour une application web moderne...',
 'Apprenez à créer des URLs propres',
 1, 89),
(2, 'Sécuriser son application PHP', 'securiser-application-php',
 'La sécurité est primordiale dans toute application web. Voici les meilleures pratiques...',
 'Guide complet de sécurité PHP',
 1, 234);

-- Associations article-catégorie
INSERT INTO article_categories (article_id, category_id) VALUES
(1, 2), (1, 3),
(2, 2), (2, 3),
(3, 2), (3, 3);

-- Commentaires
INSERT INTO comments (article_id, user_id, author_name, author_email, content, approved) VALUES
(1, 2, 'John Doe', 'john@example.com', 'Excellent article ! Très bien expliqué.', 1),
(1, 3, 'Jane Smith', 'jane@example.com', 'Merci pour ce tutoriel clair et précis.', 1),
(2, 3, 'Jane Smith', 'jane@example.com', 'Le système de routage fonctionne parfaitement !', 1);

-- =====================================================
-- VÉRIFICATION
-- =====================================================
-- Afficher les tables créées
SHOW TABLES;

-- Compter les enregistrements
SELECT 'Users' as table_name, COUNT(*) as count FROM users
UNION ALL
SELECT 'Articles', COUNT(*) FROM articles
UNION ALL
SELECT 'Categories', COUNT(*) FROM categories
UNION ALL
SELECT 'Comments', COUNT(*) FROM comments;

-- =====================================================
-- FIN DU SCRIPT
-- =====================================================
