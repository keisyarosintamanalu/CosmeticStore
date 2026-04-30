<?php
include_once "../config/database.php";
include_once "../models/Produk.php";

$db = (new Database())->connect();
$model = new Produk($db);

if(isset($_POST['simpan'])){

    $nama  = $_POST['nama'];
    $harga = $_POST['harga']; // tetap pakai angka asli (contoh: 38000)
    $stok  = $_POST['stok'];

    // 📷 upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if(!empty($gambar)){
        move_uploaded_file($tmp, "../uploads/" . $gambar);
    } else {
        $gambar = "no-image.png"; // fallback
    }

    $model->create($nama, $harga, $stok, $gambar);

    header("Location: ../index.php");
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#fff0f5; }
.form-card {
    max-width:400px;
    margin:auto;
    margin-top:50px;
    padding:25px;
    border-radius:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
    background:white;
}
.btn-pink { background:#ff69b4; color:white; }
</style>

<div class="form-card">

<h4 class="text-center text-danger mb-3">💄 Tambah Produk</h4>

<form method="POST" enctype="multipart/form-data">

    <label>Nama Produk</label>
    <input type="text" name="nama" class="form-control mb-2" required>

    <label>Harga</label>
    <input type="number" name="harga" class="form-control mb-2" 
           placeholder="Contoh: 38000" required>

    <label>Stok</label>
    <input type="number" name="stok" class="form-control mb-2" required>

    <label>Gambar</label>
    <input type="file" name="gambar" class="form-control mb-3">

    <button type="submit" name="simpan" class="btn btn-pink w-100">
        Simpan
    </button>

</form>

</div>