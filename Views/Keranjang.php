<?php
session_start();
include_once "../controllers/ProdukController.php";

$controller = new ProdukController();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_GET['tambah'])){
    $id = $_GET['tambah'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
}

echo "<h2>Keranjang</h2>";

$total = 0;

foreach($_SESSION['cart'] as $id => $qty){
    $data = $controller->model->getById($id);
    $row = $data->fetch(PDO::FETCH_ASSOC);

    $subtotal = $row['harga'] * $qty;
    $total += $subtotal;

    echo $row['nama_produk']." | Qty: $qty | Rp $subtotal <br>";
}

echo "<h3>Total: Rp $total</h3>";
?>

<a href="checkout.php">Checkout</a>