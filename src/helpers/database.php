<?php
/**
 * =====================================================
 * HELPER BASE DE DONNÉES (PDO)
 * =====================================================
 * Fonctions pour gérer la connexion et les requêtes
 */

/**
 * Crée et retourne une connexion à la base de données
 *
 * @return PDO Connexion PDO
 */
function getDbConnection()
{
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if (ENVIRONMENT === 'development') {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        } else {
            logMessage("Erreur de connexion DB : " . $e->getMessage(), 'error');
            die("Une erreur est survenue. Veuillez réessayer plus tard.");
        }
    }
}

/**
 * Ferme une connexion à la base de données
 *
 * @param PDO $connection Connexion à fermer
 * @return void
 */
function closeDbConnection(PDO &$connection)
{
    $connection = null;
}

/**
 * Exécute une requête SELECT et retourne tous les résultats
 *
 * @param PDO $connection Connexion à la base
 * @param string $query Requête SQL
 * @param array $params Paramètres pour la requête préparée
 * @return array Tableau de résultats
 */
function dbQuery(PDO $connection, string $query, array $params = []): array
{
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        logMessage("Erreur de requête : " . $e->getMessage(), 'error');
        return [];
    }
}

/**
 * Exécute une requête SELECT et retourne une seule ligne
 *
 * @param PDO $connection Connexion à la base
 * @param string $query Requête SQL
 * @param array $params Paramètres
 * @return array|null Une ligne ou null
 */
function dbQueryOne(PDO $connection, string $query, array $params = []): ?array
{
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    } catch (PDOException $e) {
        logMessage("Erreur de requête : " . $e->getMessage(), 'error');
        return null;
    }
}

/**
 * Exécute une requête INSERT, UPDATE ou DELETE
 *
 * @param PDO $connection Connexion à la base
 * @param string $query Requête SQL
 * @param array $params Paramètres
 * @return bool|int Succès ou ID inséré pour INSERT
 */
function dbExecute(PDO $connection, string $query, array $params = []): int|bool
{
    try {
        $stmt = $connection->prepare($query);
        $stmt->execute($params);

        // Pour INSERT, retourner l'ID
        $insertId = $connection->lastInsertId();
        return $insertId > 0 ? (int) $insertId : true;
    } catch (PDOException $e) {
        logMessage("Erreur d'exécution : " . $e->getMessage(), 'error');
        return false;
    }
}

/**
 * Compte le nombre de résultats
 *
 * @param PDO $connection Connexion
 * @param string $table Nom de la table
 * @param string $condition Condition WHERE (optionnelle)
 * @param array $params Paramètres de la condition
 * @return int Nombre de résultats
 */
function dbCount(PDO $connection, string $table, string $condition = '', array $params = []): int
{
    $query = "SELECT COUNT(*) as total FROM $table";

    if (!empty($condition)) {
        $query .= " WHERE $condition";
    }

    $result = dbQueryOne($connection, $query, $params);

    return $result ? (int) $result['total'] : 0;
}
