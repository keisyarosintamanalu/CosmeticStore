<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

if(empty($_SESSION['cart'])){
    echo "<h5 class='text-center mt-5'>🛒 Keranjang kosong</h5>";
    exit;
}

$total = 0;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
<h3>🛒 Keranjang</h3>

<table class="table align-middle">
<tr>
<th>Produk</th>
<th>Qty</th>
<th>Subtotal</th>
</tr>

<?php foreach($_SESSION['cart'] as $id=>$qty):

$stmt=$db->prepare("SELECT * FROM produk WHERE id=:id");
$stmt->execute(['id'=>$id]);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$row){
    continue;
}

$sub=$row['harga']*$qty;
$total+=$sub;
?>

<tr>

<td>
    <div class="d-flex align-items-center gap-2">
        <img src="../uploads/<?= $row['gambar'] ?: 'default.png'; ?>" 
             style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
        <div><?= $row['nama_produk']; ?></div>
    </div>
</td>

<td>
<a href="../index.php?tambah=<?= $id; ?>" class="btn btn-success btn-sm">+</a>
<span class="mx-2"><?= $qty; ?></span>
<a href="../index.php?hapus=<?= $id; ?>" class="btn btn-danger btn-sm">-</a>
</td>

<td>Rp <?= number_format($sub,0,',','.'); ?></td>

</tr>

<?php endforeach; ?>

</table>

<h4>Total: Rp <?= number_format($total,0,',','.'); ?></h4>

<a href="checkout.php" class="btn btn-success">
Checkout
</a>

</div>