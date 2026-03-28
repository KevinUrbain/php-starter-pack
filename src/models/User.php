<?php
/**
 * =====================================================
 * MODÈLE USER
 * =====================================================
 * Gère toutes les opérations liées aux utilisateurs
 * 
 * Ce fichier montre comment structurer un modèle en procédural
 * Chaque fonction = une opération sur les données
 */

/**
 * Récupère tous les utilisateurs
 * 
 * @return array Liste des utilisateurs
 */
function getAllUsers()
{
    $db = getDbConnection();

    $query = "SELECT id, name, email, created_at FROM users ORDER BY created_at DESC";
    $users = dbQuery($db, $query);

    closeDbConnection($db);

    return $users;
}

/**
 * Récupère un utilisateur par son ID
 * 
 * @param int $id ID de l'utilisateur
 * @return array|null Utilisateur ou null
 */
function getUserById(int $id)
{
    $db = getDbConnection();

    $query = "SELECT id, name, email, created_at FROM users WHERE id = ?";
    $user = dbQueryOne($db, $query, [$id]);

    closeDbConnection($db);

    return $user;
}

/**
 * Récupère un utilisateur par son email
 * 
 * @param string $email Email de l'utilisateur
 * @return array|null Utilisateur ou null
 */
function getUserByEmail(string $email)
{
    $db = getDbConnection();

    $query = "SELECT * FROM users WHERE email = ?";
    $user = dbQueryOne($db, $query, [$email]);

    closeDbConnection($db);

    return $user;
}

/**
 * Crée un nouvel utilisateur
 * 
 * @param array $data Données de l'utilisateur
 * @return int|false ID de l'utilisateur créé ou false
 */
function createUser(array $data)
{
    $db = getDbConnection();

    // Hasher le mot de passe
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
    $userId = dbExecute($db, $query, [
        $data['name'],
        $data['email'],
        $hashedPassword
    ]);

    closeDbConnection($db);

    return $userId;
}

/**
 * Met à jour un utilisateur
 * 
 * @param int $id ID de l'utilisateur
 * @param array $data Nouvelles données
 * @return bool Succès de l'opération
 */
function updateUser(int $id, array $data)
{
    $db = getDbConnection();

    $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $success = dbExecute($db, $query, [
        $data['name'],
        $data['email'],
        $id
    ]);

    closeDbConnection($db);

    return $success;
}

/**
 * Supprime un utilisateur
 * 
 * @param int $id ID de l'utilisateur
 * @return bool Succès de l'opération
 */
function deleteUser($id)
{
    $db = getDbConnection();

    $query = "DELETE FROM users WHERE id = ?";
    $success = dbExecute($db, $query, [$id]);

    closeDbConnection($db);

    return $success;
}

/**
 * Vérifie si un email existe déjà
 * 
 * @param string $email Email à vérifier
 * @param int|null $excludeId ID à exclure (pour les mises à jour)
 * @return bool
 */
function emailExists(string $email, ?int $excludeId = null)
{
    $db = getDbConnection();

    if ($excludeId) {
        $query = "SELECT COUNT(*) as total FROM users WHERE email = ? AND id != ?";
        $result = dbQueryOne($db, $query, [$email, $excludeId]);
    } else {
        $query = "SELECT COUNT(*) as total FROM users WHERE email = ?";
        $result = dbQueryOne($db, $query, [$email]);
    }

    closeDbConnection($db);

    return $result && $result['total'] > 0;
}

/**
 * Authentifie un utilisateur
 * 
 * @param string $email Email
 * @param string $password Mot de passe
 * @return array|false Utilisateur si authentifié, false sinon
 */
function authenticateUser(string $email, string $password)
{
    $user = getUserByEmail($email);

    if (!$user) {
        return false;
    }

    // Vérifier le mot de passe
    if (password_verify($password, $user['password'])) {
        // Ne pas retourner le mot de passe
        unset($user['password']);
        return $user;
    }

    return false;
}

/**
 * Compte le nombre total d'utilisateurs
 * 
 * @return int Nombre d'utilisateurs
 */
function countUsers()
{
    $db = getDbConnection();
    $count = dbCount($db, 'users');
    closeDbConnection($db);

    return $count;
}

/**
 * Récupère les utilisateurs avec pagination
 * 
 * @param int $page Numéro de page
 * @param int $perPage Nombre par page
 * @return array Utilisateurs
 */
function getUsersPaginated(int $page = 1, int $perPage = 10)
{
    $db = getDbConnection();

    $offset = ($page - 1) * $perPage;

    $query = "SELECT id, name, email, created_at FROM users 
              ORDER BY created_at DESC 
              LIMIT ? OFFSET ?";

    $users = dbQuery($db, $query, [$perPage, $offset]);

    closeDbConnection($db);

    return $users;
}

/**
 * Recherche des utilisateurs
 * 
 * @param string $search Terme de recherche
 * @return array Utilisateurs trouvés
 */
function searchUsers(string $search)
{
    $db = getDbConnection();

    $searchTerm = "%$search%";

    $query = "SELECT id, name, email, created_at FROM users 
              WHERE name LIKE ? OR email LIKE ?
              ORDER BY created_at DESC";

    $users = dbQuery($db, $query, [$searchTerm, $searchTerm]);

    closeDbConnection($db);

    return $users;
}

/**
 * Change le mot de passe d'un utilisateur
 * 
 * @param int $userId ID de l'utilisateur
 * @param string $newPassword Nouveau mot de passe
 * @return bool Succès de l'opération
 */
function changeUserPassword(int $userId, string $newPassword)
{
    $db = getDbConnection();

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $query = "UPDATE users SET password = ? WHERE id = ?";
    $success = dbExecute($db, $query, [$hashedPassword, $userId]);

    closeDbConnection($db);

    return $success;
}
