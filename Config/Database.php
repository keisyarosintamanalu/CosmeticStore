<?php
class Database {

    private $alamat = "127.0.0.1";
    private $db   = "cosmetik_store_Keisya"; 
    private $user = "root";
    private $pass = "";

    public function connect() {
        try {
            $conn = new PDO("mysql:host=$this->alamat;dbname=$this->db", $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
}
?>