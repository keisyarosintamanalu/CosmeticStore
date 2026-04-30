<?php
class Database {

    private $host = "localhost";
    private $db   = "cosmetik_store"; // SESUAI TERMINAL KAMU
    private $user = "root";
    private $pass = "";

    public function connect() {
        try {
            $conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
}
?>