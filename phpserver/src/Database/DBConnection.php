<?php
// src/Database/DBConnection.php
// Secure PDO wrapper for all database operations

require_once __DIR__ . '/../Config/config.php';

class DBConnection {
    private $pdo;

    public function getPDO() {
        return $this->pdo;
    }

    public function __construct() {
        $host = Config::get('DB_HOST');
        $db   = Config::get('DB_NAME');
        $user = Config::get('DB_USER');
        $pass = Config::get('DB_PASS');
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public function executeRead($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function fetchSingle($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function executeWrite($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}

// Usage example:
// $db = new DBConnection();
// $rows = $db->executeRead('SELECT * FROM students WHERE department = ?', ['dce']);
