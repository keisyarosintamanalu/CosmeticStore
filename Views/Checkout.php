<?php
session_start();
include_once "../config/Database.php";

$db = (new Database())->connect();

if(empty($_SESSION['cart'])){
    echo "<div class='alert alert-warning'>Keranjang kosong!</div>";
    exit;
}

$total = 0;
$voucher = 0;

foreach($_SESSION['cart'] as $id=>$qty){

    $stmt = $db->prepare("SELECT * FROM produk WHERE id=:id");
    $stmt->execute(['id'=>$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row){
        continue;
    }

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

        $stmt = $db->prepare("SELECT * FROM produk WHERE id=:id FOR UPDATE");
        $stmt->execute(['id'=>$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row){
            throw new Exception("Produk tidak ditemukan!");
        }

        if($row['stok'] < $qty){
            throw new Exception("Stok {$row['nama_produk']} tidak cukup!");
        }

        $db->prepare("
            UPDATE produk SET 
            stok = stok - :q,
            terjual = terjual + :q
            WHERE id=:id
        ")->execute([
            'q'=>$qty,
            'id'=>$id
        ]);
    }

    $stmt = $db->prepare("INSERT INTO transaksi(total,status) VALUES(:t,'Diproses')");
    $stmt->execute(['t'=>$grand]);

    $transaksi_id = $db->lastInsertId();

    $db->commit();

    $_SESSION['cart'] = [];

    echo "
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js'></script>

    <audio id='successSound'>
    <source src='https://cdn.pixabay.com/download/audio/2022/03/15/audio_3e0b3a7d7e.mp3' type='audio/mpeg'>
    </audio>

    <script>

    document.getElementById('successSound').play();

    confetti({
        particleCount:150,
        spread:70,
        origin:{y:0.6}
    });

    Swal.fire({
        title:'Hore! 🎉',
        text:'Pesananmu telah berhasil! 👏👏',
        icon:'success',
        confirmButtonColor:'#ff69b4',
        confirmButtonText:'Lihat Status'
    }).then(()=>{
        window.location='status.php?id=$transaksi_id';
    });

    setTimeout(()=>{
        fetch('update_status.php?id=$transaksi_id&status=Dikirim');
    },5000);

    setTimeout(()=>{
        fetch('update_status.php?id=$transaksi_id&status=Selesai');
    },10000);

    </script>
    ";

    exit;

}catch(Exception $e){
    $db->rollBack();
    echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";
}
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

<h3>Checkout</h3>

<p>Total: Rp <?= number_format($total,0,',','.'); ?></p>
<p>Voucher: Rp <?= number_format($voucher,0,',','.'); ?></p>

<h4>Bayar: Rp <?= number_format($grand,0,',','.'); ?></h4>

<form method="POST">
<button name="checkout" class="btn btn-success">Bayar</button>
</form>

</div>