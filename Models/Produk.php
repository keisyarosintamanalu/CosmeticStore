<?php
class Produk {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM produk");
    }

    public function getById($id) {
        return $this->conn->query("SELECT * FROM produk WHERE id=$id");
    }

    public function create($nama, $harga, $stok) {
        return $this->conn->query(
            "INSERT INTO produk(nama_produk, harga, stok)
             VALUES('$nama','$harga','$stok')"
        );
    }

    public function update($id, $nama, $harga, $stok) {
        return $this->conn->query(
            "UPDATE produk SET 
            nama_produk='$nama',
            harga='$harga',
            stok='$stok'
            WHERE id=$id"
        );
    }

    public function delete($id) {
        return $this->conn->query("DELETE FROM produk WHERE id=$id");
    }
}
?>