<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

$total = 0;
$voucher = 0;

foreach($_SESSION['cart'] as $id=>$qty){
$stmt=$db->prepare("SELECT * FROM produk WHERE id=:id");
$stmt->execute(['id'=>$id]);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

$total += $row['harga'] * $qty;
}

if($total >= 100000){
$voucher = 10000;
}

$grand = $total - $voucher;

if(isset($_POST['checkout'])){
try{
$db->beginTransaction();

foreach($_SESSION['cart'] as $id=>$qty){

$stmt=$db->prepare("SELECT * FROM produk WHERE id=:id FOR UPDATE");
$stmt->execute(['id'=>$id]);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

if($row['stok'] < $qty){
throw new Exception("Stok habis");
}

$db->prepare("
UPDATE produk SET 
stok = stok - :q,
terjual = terjual + :q
WHERE id=:id
")->execute(['q'=>$qty,'id'=>$id]);
}

$db->commit();
$_SESSION['cart']=[];

echo "<script>alert('Hore, pesananmu telah berhasil!');location='../index.php';</script>";

}catch(Exception $e){
$db->rollBack();
echo $e->getMessage();
}
}
?>

<div class="container mt-4">
<h3>Checkout</h3>

<p>Total: Rp <?= number_format($total); ?></p>
<p>Voucher: Rp <?= number_format($voucher); ?></p>
<h4>Bayar: Rp <?= number_format($grand); ?></h4>

<form method="POST">
<button name="checkout" class="btn btn-success">Bayar</button>
</form>
</div>