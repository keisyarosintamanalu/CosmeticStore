<?php
session_start();
include_once "../config/Database.php";

if(!isset($_SESSION['login'])){
    header("Location: Login.php");
    exit;
}

$db = (new Database())->connect();
$role = $_SESSION['role'] ?? 'customer';

$harian = $db->query("
SELECT DATE(tanggal) tgl, SUM(total) total
FROM transaksi GROUP BY DATE(tanggal)
");

$h_labels=[]; $h_data=[];
while($r=$harian->fetch(PDO::FETCH_ASSOC)){
    $h_labels[]=$r['tgl'];
    $h_data[]=$r['total'];
}

$bulanan = $db->query("
SELECT DATE_FORMAT(tanggal,'%Y-%m') bln, SUM(total) total
FROM transaksi GROUP BY bln
");

$b_labels=[]; $b_data=[];
while($r=$bulanan->fetch(PDO::FETCH_ASSOC)){
    $b_labels[]=$r['bln'];
    $b_data[]=$r['total'];
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">

<h2 class="text-center text-danger">Dashboard</h2>
<a href="../index.php" class="btn btn-secondary mb-3">← Kembali</a>

<?php if($role == 'admin'): ?>

<?php
$totalProduk = $db->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$totalTerjual = $db->query("SELECT SUM(terjual) FROM produk")->fetchColumn();
$totalTransaksi = $db->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();
$totalPendapatan = $db->query("SELECT SUM(total) FROM transaksi")->fetchColumn();
?>

<div class="row text-center mb-4">

<div class="col-md-3">
<div class="card p-3 bg-primary text-white">
<h6>Total Produk</h6>
<h4><?= $totalProduk ?></h4>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 bg-success text-white">
<h6>Total Terjual</h6>
<h4><?= $totalTerjual ?: 0 ?></h4>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 bg-warning text-white">
<h6>Transaksi</h6>
<h4><?= $totalTransaksi ?></h4>
</div>
</div>

<div class="col-md-3">
<div class="card p-3 bg-danger text-white">
<h6>Pendapatan</h6>
<h4>Rp <?= number_format($totalPendapatan ?: 0,0,',','.'); ?></h4>
</div>
</div>

</div>

<h4>Grafik Penjualan Harian</h4>
<canvas id="harian"></canvas>

<h4 class="mt-4">Grafik Penjualan Bulanan</h4>
<canvas id="bulanan"></canvas>

<script>
new Chart(document.getElementById('harian'), {
    type:'line',
    data:{
        labels: <?= json_encode($h_labels); ?>,
        datasets:[{
            label:'Penjualan Harian',
            data: <?= json_encode($h_data); ?>,
            borderWidth:2
        }]
    }
});

new Chart(document.getElementById('bulanan'), {
    type:'bar',
    data:{
        labels: <?= json_encode($b_labels); ?>,
        datasets:[{
            label:'Penjualan Bulanan',
            data: <?= json_encode($b_data); ?>,
            borderWidth:1
        }]
    }
});
</script>

<?php else: ?>

<div class="card p-4 text-center">
<h4>Halo, <?= $_SESSION['username']; ?> 👋</h4>
<p>Selamat datang di <b>Cosmetics Store</b></p>

<p>Kamu bisa mulai belanja sekarang ✨</p>

<a href="../index.php" class="btn btn-success">
🛒 Mulai Belanja
</a>
</div>

<?php endif; ?>

</div>