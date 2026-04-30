<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

if(empty($_SESSION['cart'])){
    die("Keranjang kosong");
}

$total = 0;
$voucher = 0;

foreach($_SESSION['cart'] as $id => $qty){
    $stmt = $db->prepare("SELECT * FROM produk WHERE id=:id");
    $stmt->execute(['id'=>$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row) continue;

    $total += $row['harga'] * $qty;
}

if($total >= 100000){
    $voucher = 10000;
}

$grand_total = $total - $voucher;

if(isset($_POST['checkout'])){
    try {
        $db->beginTransaction();

        foreach($_SESSION['cart'] as $id => $qty){

            $stmt = $db->prepare("SELECT * FROM produk WHERE id=:id FOR UPDATE");
            $stmt->execute(['id'=>$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$row){
                throw new Exception("Produk tidak ditemukan");
            }

            if($row['stok'] < $qty){
                throw new Exception("Stok {$row['nama_produk']} tidak cukup!");
            }
        }

        $stmt = $db->prepare("INSERT INTO transaksi(total) VALUES(:total)");
        $stmt->execute(['total'=>$grand_total]);
        $transaksi_id = $db->lastInsertId();

        foreach($_SESSION['cart'] as $id => $qty){

            $stmt = $db->prepare("SELECT * FROM produk WHERE id=:id");
            $stmt->execute(['id'=>$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $subtotal = $row['harga'] * $qty;

            $stmt = $db->prepare("
                INSERT INTO detail_transaksi(transaksi_id,produk_id,qty,subtotal)
                VALUES(:t,:p,:q,:s)
            ");
            $stmt->execute([
                't'=>$transaksi_id,
                'p'=>$id,
                'q'=>$qty,
                's'=>$subtotal
            ]);

            $stmt = $db->prepare("
                UPDATE produk 
                SET stok = stok - :qty,
                    terjual = terjual + :qty
                WHERE id=:id
            ");
            $stmt->execute([
                'qty'=>$qty,
                'id'=>$id
            ]);
        }

        $db->commit();

        $_SESSION['cart'] = [];

        echo "<script>
            alert('Hore, pesananmu telah berhasil!');
            window.location='struk.php?id=$transaksi_id';
        </script>";
        exit;

    } catch(Exception $e){
        $db->rollBack();
        echo "<h5 class='text-danger'>".$e->getMessage()."</h5>";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

<h3 class="text-center text-danger">Checkout</h3>

<div class="card p-3 shadow">

<h5>Total Belanja:</h5>
<p>Rp <?= number_format($total,0,',','.'); ?></p>

<h5>Voucher:</h5>
<p class="text-success">
Rp <?= number_format($voucher,0,',','.'); ?>
</p>

<hr>

<h4 class="text-danger">
Total Bayar: Rp <?= number_format($grand_total,0,',','.'); ?>
</h4>

<form method="POST">
<button name="checkout" class="btn btn-success w-100 mt-2">
Checkout Sekarang
</button>
</form>

</div>

</div>