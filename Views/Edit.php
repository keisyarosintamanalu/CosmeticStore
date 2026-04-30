<?php
include_once "../controllers/ProdukController.php";
$controller = new ProdukController();

$id = $_GET['id'];

$data = $controller->model->getById($id);
$row = $data->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['update'])){

    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    // upload gambar baru (opsional)
    if($_FILES['gambar']['name'] != ""){
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/".$gambar);
    } else {
        $gambar = $row['gambar'];
    }

    $controller->model->update($id,$nama,$harga,$stok,$gambar);

    header("Location: ../index.php");
}
?>

<form method="POST" enctype="multipart/form-data">

Nama:
<input type="text" name="nama" value="<?= $row['nama_produk']; ?>"><br>

Harga:
<input type="number" name="harga" value="<?= $row['harga']; ?>"><br>

Stok:
<input type="number" name="stok" value="<?= $row['stok']; ?>"><br>

Gambar:
<input type="file" name="gambar"><br>

<img src="../uploads/<?= $row['gambar']; ?>" width="100"><br>

<button name="update">Update</button>

</form>