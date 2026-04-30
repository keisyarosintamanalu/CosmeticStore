<?php
class Produk {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

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

    public function getById($id){
        $sql = "SELECT * FROM produk WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);

        // ✅ WAJIB pakai fetch (biar tidak error di cart dropdown)
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nama, $harga, $stok, $gambar){
        $sql = "INSERT INTO produk 
                (nama_produk, harga, stok, gambar, likes, rating, terjual)
                VALUES (:nama, :harga, :stok, :gambar, 0, 0, 0)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'nama'   => $nama,
            'harga'  => $harga,
            'stok'   => $stok,
            'gambar' => $gambar ?: 'default.png' // fallback gambar
        ]);
    }

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
            'gambar' => $gambar ?: 'default.png'
        ]);
    }

    public function delete($id){
        $sql = "DELETE FROM produk WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function like($id){
        $sql = "UPDATE produk SET likes = likes + 1 WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function rate($id, $rating){
        $sql = "UPDATE produk SET rating = :rating WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id'     => $id,
            'rating' => $rating
        ]);
    }

    public function updateStokTerjual($id, $qty){
        $sql = "UPDATE produk 
                SET stok = stok - :qty,
                    terjual = terjual + :qty
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id'  => $id,
            'qty' => $qty
        ]);
    }

}
?>