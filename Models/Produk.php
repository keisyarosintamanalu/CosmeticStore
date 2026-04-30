<?php
class Produk {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // 🔍 Ambil semua data + search
    public function getAll($search = ""){
        $sql = "SELECT * FROM produk 
                WHERE nama_produk LIKE :search
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'search' => "%$search%"
        ]);

        return $stmt;
    }

    // 🔎 Ambil 1 data berdasarkan ID (WAJIB untuk edit & keranjang)
    public function getById($id){
        $sql = "SELECT * FROM produk WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);

        return $stmt;
    }

    // ➕ Tambah produk (dengan gambar)
    public function create($nama, $harga, $stok, $gambar){
        $sql = "INSERT INTO produk 
                (nama_produk, harga, stok, gambar, likes, rating)
                VALUES (:nama, :harga, :stok, :gambar, 0, 0)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'nama'   => $nama,
            'harga'  => $harga,
            'stok'   => $stok,
            'gambar' => $gambar
        ]);
    }

    // ✏️ Update produk
    public function update($id, $nama, $harga, $stok, $gambar){
        $sql = "UPDATE produk SET
                nama_produk = :nama,
                harga       = :harga,
                stok        = :stok,
                gambar      = :gambar
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id'     => $id,
            'nama'   => $nama,
            'harga'  => $harga,
            'stok'   => $stok,
            'gambar' => $gambar
        ]);
    }

    // ❌ Hapus produk
    public function delete($id){
        $sql = "DELETE FROM produk WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }

    // 💖 Like produk (tambah 1)
    public function like($id){
        $sql = "UPDATE produk SET likes = likes + 1 WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }

    // ⭐ Rating produk
    public function rate($id, $rating){
        $sql = "UPDATE produk SET rating = :rating WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id'     => $id,
            'rating' => $rating
        ]);
    }

}
?>