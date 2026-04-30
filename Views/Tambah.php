<?php
include_once "../controllers/ProdukController.php";

$controller = new ProdukController();

if(isset($_POST['simpan'])) {
    $controller->model->create(
        $_POST['nama'],
        $_POST['harga'],
        $_POST['stok']
    );

    header("Location: ../index.php");
}
?>

<h2>Tambah Produk</h2>

<form method="POST">
Nama Produk:
<input type="text" name="nama"><br><br>

Harga:
<input type="number" name="harga"><br><br>

Stok:
<input type="number" name="stok"><br><br>

<button type="submit" name="simpan">Simpan</button>
</form>