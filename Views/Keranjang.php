<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

if(empty($_SESSION['cart'])){
    echo "Keranjang kosong";
    exit;
}

$total = 0;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
<h3>Keranjang</h3>

<table class="table">
<tr>
<th>Produk</th>
<th>Qty</th>
<th>Subtotal</th>
</tr>

<?php foreach($_SESSION['cart'] as $id=>$qty):

$stmt=$db->prepare("SELECT * FROM produk WHERE id=:id");
$stmt->execute(['id'=>$id]);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

$sub=$row['harga']*$qty;
$total+=$sub;
?>

<tr>
<td><?= $row['nama_produk']; ?></td>

<td>
<a href="../index.php?tambah=<?= $id; ?>" class="btn btn-success btn-sm">+</a>
<?= $qty; ?>
<a href="../index.php?hapus=<?= $id; ?>" class="btn btn-danger btn-sm">-</a>
</td>

<td>Rp <?= number_format($sub); ?></td>
</tr>

<?php endforeach; ?>

</table>

<h4>Total: Rp <?= number_format($total); ?></h4>

<a href="checkout.php" class="btn btn-success">Checkout</a>

</div>