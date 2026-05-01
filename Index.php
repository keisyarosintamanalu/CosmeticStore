<?php 
session_start();

include_once "controllers/ProdukController.php";
$controller = new ProdukController();

if(!isset($_SESSION['login'])){
    header("Location: views/Login.php");
    exit;
}

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_GET['tambah'])){
    $id = (int)$_GET['tambah'];

    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    header("Location: index.php");
    exit;
}

if(isset($_GET['like'])){
    $controller->model->like($_GET['like']);
    header("Location: index.php");
    exit;
}

if(isset($_POST['rating'])){
    $id     = $_POST['id'];
    $rating = (int)$_POST['rating'];

    $controller->model->rate($id, $rating);

    header("Location: index.php");
    exit;
}

if(isset($_GET['hapus'])){
    $controller->model->delete($_GET['hapus']);
    header("Location: index.php");
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-3">
    <h5>Halo, <?= $_SESSION['username']; ?> 👋</h5>

    <a href="views/Keranjang.php" class="btn btn-success">🛒 Keranjang</a>
    <a href="views/Dashboard.php" class="btn btn-info">📊 Dashboard</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<hr>

<?php include_once "views/List.php"; ?>