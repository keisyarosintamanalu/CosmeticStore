<?php
include_once "../config/Database.php";
$db = (new Database())->connect();

/* HARIAN */
$harian = $db->query("
SELECT DATE(tanggal) tgl, SUM(total) total
FROM transaksi GROUP BY DATE(tanggal)
");

$h_labels=[]; $h_data=[];
while($r=$harian->fetch(PDO::FETCH_ASSOC)){
    $h_labels[]=$r['tgl'];
    $h_data[]=$r['total'];
}

/* BULANAN */
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h2>Grafik Harian</h2>
<canvas id="harian"></canvas>

<h2>Grafik Bulanan</h2>
<canvas id="bulanan"></canvas>

<script>
new Chart(document.getElementById('harian'), {
    type:'line',
    data:{
        labels: <?= json_encode($h_labels); ?>,
        datasets:[{label:'Harian', data: <?= json_encode($h_data); ?>}]
    }
});

new Chart(document.getElementById('bulanan'), {
    type:'bar',
    data:{
        labels: <?= json_encode($b_labels); ?>,
        datasets:[{label:'Bulanan', data: <?= json_encode($b_data); ?>}]
    }
});
</script>