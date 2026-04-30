<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

$total = 0;

foreach($_SESSION['cart'] as $id => $qty){
    $data = $db->query("SELECT * FROM produk WHERE id=$id");
    $row = $data->fetch(PDO::FETCH_ASSOC);

    $subtotal = $row['harga'] * $qty;
    $total += $subtotal;
}

$db->query("INSERT INTO transaksi(total) VALUES('$total')");
$transaksi_id = $db->lastInsertId();

foreach($_SESSION['cart'] as $id => $qty){
    $data = $db->query("SELECT * FROM produk WHERE id=$id");
    $row = $data->fetch(PDO::FETCH_ASSOC);

    $subtotal = $row['harga'] * $qty;

    $db->query("INSERT INTO detail_transaksi(transaksi_id,produk_id,qty,subtotal)
                VALUES('$transaksi_id','$id','$qty','$subtotal')");
}

$_SESSION['cart'] = [];

echo "<h2>Transaksi Berhasil!</h2>";
echo "<a href='struk.php?id=$transaksi_id'>Cetak Struk</a>";
?>