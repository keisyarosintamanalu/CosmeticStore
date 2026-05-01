<?php
include_once "../config/Database.php";
include_once "../models/Produk.php";

$db = (new Database())->connect();
$model = new Produk($db);

if(isset($_POST['simpan'])){

    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if($gambar){
        move_uploaded_file($tmp, "../uploads/".$gambar);
    } else {
        $gambar = "default.png";
    }

    $model->create($nama, $harga, $stok, $gambar);

    echo "<script>
        alert('Produk berhasil ditambahkan!');
        window.location='../index.php';
    </script>";
}
?>

<form method="POST" enctype="multipart/form-data">
<input type="text" name="nama" placeholder="Nama Produk" required>
<input type="number" name="harga" placeholder="Harga" required>
<input type="number" name="stok" placeholder="Stok" required>
<input type="file" name="gambar">
<button name="simpan">Simpan</button>
</form>