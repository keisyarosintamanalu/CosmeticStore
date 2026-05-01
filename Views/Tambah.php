<?php
include_once "../config/Database.php";
include_once "../models/Produk.php";

$db = (new Database())->connect();
$model = new Produk($db);

if(isset($_POST['simpan'])){

$nama  = $_POST['nama'];
$harga = $_POST['harga'];
$stok  = $_POST['stok'];

/* UPLOAD GAMBAR */
$gambar = $_FILES['gambar']['name'];
$tmp    = $_FILES['gambar']['tmp_name'];

if($gambar){
move_uploaded_file($tmp, "../uploads/".$gambar);
}else{
$gambar = "default.png";
}

$model->create($nama,$harga,$stok,$gambar);

header("Location: ../index.php");
exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

<div class="card p-4 shadow" style="max-width:500px;margin:auto;">

<h4 class="text-center mb-3">Tambah Produk</h4>

<form method="POST" enctype="multipart/form-data">

<label>Nama Produk</label>
<input type="text" name="nama" class="form-control mb-2" required>

<label>Harga</label>
<input type="number" name="harga" class="form-control mb-2" required>

<label>Stok</label>
<input type="number" name="stok" class="form-control mb-2" required>

<label>Gambar</label>
<input type="file" name="gambar" class="form-control mb-3">

<button name="simpan" class="btn btn-success w-100">Simpan</button>

</form>

</div>
</div>