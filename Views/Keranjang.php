<?php
session_start();
include_once "../controllers/ProdukController.php";

$controller = new ProdukController();

echo "<h2>Keranjang</h2>";

$total = 0;

if(empty($_SESSION['cart'])){
    echo "Kosong";
} else {

    foreach($_SESSION['cart'] as $id => $qty){

        $data = $controller->model->getById($id);
        $row = $data->fetch(PDO::FETCH_ASSOC);

        $subtotal = $row['harga'] * $qty;
        $total += $subtotal;

        echo $row['nama_produk']." | Qty: $qty | Rp ".number_format($subtotal)."<br>";
    }

    echo "<h3>Total: Rp ".number_format($total)."</h3>";
    echo "<a href='Checkout.php'>Checkout</a>";
}
?>