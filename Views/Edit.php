<?php
include_once "../config/Database.php";
include_once "../models/Produk.php";

$db = (new Database())->connect();
$model = new Produk($db);

$id = $_GET['id'];
$data = $model->getById($id);

if(!$data){
    die("Data tidak ditemukan");
}

if(isset($_POST['update'])){

    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $gambar = $data['gambar']; // default gambar lama

    if($_FILES['gambar']['name']){
        $file = $_FILES['gambar']['name'];
        $tmp  = $_FILES['gambar']['tmp_name'];

        move_uploaded_file($tmp, "../uploads/".$file);
        $gambar = $file;
    }

    $model->update($id, $nama, $harga, $stok, $gambar);

    echo "<script>
        alert('Produk berhasil diupdate!');
        window.location='../index.php';
    </script>";
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">

<div class="card shadow p-4" style="max-width:500px;margin:auto;border-radius:15px;">

<h3 class="text-center text-danger mb-4">✏ Edit Produk</h3>

<form method="POST" enctype="multipart/form-data">

<label class="form-label fw-bold">Nama Produk</label>
<input type="text" name="nama" class="form-control mb-3"
value="<?= $data['nama_produk']; ?>" required>

<label class="form-label fw-bold">Harga (Rp)</label>
<input type="number" name="harga" class="form-control mb-3"
value="<?= $data['harga']; ?>" required>

<label class="form-label fw-bold">Stok</label>
<input type="number" name="stok" class="form-control mb-3"
value="<?= $data['stok']; ?>" required>

<label class="form-label fw-bold">Gambar Produk</label>

<div class="mb-3 text-center">
<img src="../uploads/<?= $data['gambar']; ?>"
width="120" style="border-radius:10px;">
</div>

<input type="file" name="gambar" class="form-control mb-3">

<small class="text-muted">
Kosongkan jika tidak ingin mengganti gambar
</small>

<button name="update" class="btn btn-success w-100 mt-3">
💾 Simpan Perubahan
</button>

<a href="../index.php" class="btn btn-secondary w-100 mt-2">
⬅ Kembali
</a>

</form>

</div>

</div>