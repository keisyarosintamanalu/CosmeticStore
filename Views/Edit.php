<?php
include_once "../controllers/ProdukController.php";

$controller = new ProdukController();

$id = $_GET['id'];

$data = $controller->model->getById($id);
$row = $data->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['update'])) {

    $controller->model->update(
        $id,
        $_POST['nama'],
        $_POST['harga'],
        $_POST['stok']
    );

    header("Location: ../index.php");
}
?>

<h2>Edit Produk</h2>

<form method="POST">

Nama:
<input type="text" name="nama" value="<?= $row['nama_produk']; ?>"><br><br>

Harga:
<input type="number" name="harga" value="<?= $row['harga']; ?>"><br><br>

Stok:
<input type="number" name="stok" value="<?= $row['stok']; ?>"><br><br>

<button type="submit" name="update">Update</button>

</form>