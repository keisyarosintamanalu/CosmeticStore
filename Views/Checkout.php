<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

if(empty($_SESSION['cart'])){
    die("Keranjang kosong");
}

try {
    $db->beginTransaction();

    $total = 0;

    // hitung total + validasi stok
    foreach($_SESSION['cart'] as $id => $qty){
        $stmt = $db->prepare("SELECT * FROM produk WHERE id=:id FOR UPDATE");
        $stmt->execute(['id'=>$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) throw new Exception("Produk tidak ditemukan (ID: $id)");

        if($row['stok'] < $qty){
            throw new Exception("Stok {$row['nama_produk']} tidak cukup!");
        }

        $total += $row['harga'] * $qty;
    }

    // simpan transaksi
    $stmt = $db->prepare("INSERT INTO transaksi(total) VALUES(:total)");
    $stmt->execute(['total'=>$total]);
    $transaksi_id = $db->lastInsertId();

    // simpan detail + kurangi stok
    foreach($_SESSION['cart'] as $id => $qty){
        $stmt = $db->prepare("SELECT * FROM produk WHERE id=:id");
        $stmt->execute(['id'=>$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $subtotal = $row['harga'] * $qty;

        // detail_transaksi
        $stmt = $db->prepare("
            INSERT INTO detail_transaksi(transaksi_id,produk_id,qty,subtotal)
            VALUES(:t,:p,:q,:s)
        ");
        $stmt->execute([
            't'=>$transaksi_id,
            'p'=>$id,
            'q'=>$qty,
            's'=>$subtotal
        ]);

        // 🔻 KURANGI STOK
        $stmt = $db->prepare("
            UPDATE produk SET stok = stok - :qty WHERE id=:id
        ");
        $stmt->execute([
            'qty'=>$qty,
            'id'=>$id
        ]);
    }

    $db->commit();

    // kosongkan cart
    $_SESSION['cart'] = [];

    echo "<h2>Transaksi berhasil!</h2>";
    echo "<a href='struk.php?id=$transaksi_id'>Cetak Struk</a>";

} catch(Exception $e){
    $db->rollBack();
    echo "Gagal checkout: ".$e->getMessage();
}