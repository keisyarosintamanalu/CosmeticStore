<?php
include_once "../config/Database.php";
$db = (new Database())->connect();

$id = $_GET['id'];

$trans = $db->query("SELECT * FROM transaksi WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
$detail = $db->query("SELECT * FROM detail_transaksi WHERE transaksi_id=$id");
?>

<h2>Struk Pembelian</h2>
Tanggal: <?= $trans['tanggal']; ?><br><br>

<?php
$total = 0;
while($d = $detail->fetch(PDO::FETCH_ASSOC)){
    $produk = $db->query("SELECT nama_produk FROM produk WHERE id=".$d['produk_id'])->fetch(PDO::FETCH_ASSOC);

    echo $produk['nama_produk']." | Qty: ".$d['qty']." | Rp ".$d['subtotal']."<br>";
    $total += $d['subtotal'];
}
?>

<h3>Total: Rp <?= $total; ?></h3>

<button onclick="window.print()">🖨️ Print</button>