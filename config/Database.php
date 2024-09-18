<?php

class Database {
    private $dbHost = "localhost";
    private $dbName = "NextQuestion";
    private $dbUser = "root";
    private $dbUserPassword = "";
    private $conn;

    public function __construct() {
        $this->conn = null;
    }

    public function closeConn() {
        $this->conn = null;
    }

    public function getConn() {
                
        try {
            $this->conn = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUser, $this->dbUserPassword);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }

    public static function select_query($conn, $sql) {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$conn = new Database();
$conn = $conn->getConn();