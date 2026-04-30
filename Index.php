<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: views/login.php");
    exit;
}

include_once "controllers/ProdukController.php";

$controller = new ProdukController();

if(isset($_GET['hapus'])){
    $controller->model->delete($_GET['hapus']);
    header("Location: index.php");
}
?>

<div class="container mt-3">
    <h5>Halo, <?= $_SESSION['username']; ?> 👋</h5>

    <a href="views/keranjang.php" class="btn btn-success">🛒 Keranjang</a>
    <a href="views/dashboard.php" class="btn btn-info">📊 Dashboard</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<hr>

<?php include_once "views/list.php"; ?>