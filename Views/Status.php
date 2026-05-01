<?php
include_once "../config/Database.php";
$db = (new Database())->connect();

$id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM transaksi WHERE id=:id");
$stmt->execute(['id'=>$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5 text-center">

<h2>Status Pesanan</h2>

<h4>📦 <?= $data['status']; ?></h4>

<p>ID Transaksi: <?= $id; ?></p>

<a href="../index.php" class="btn btn-primary">Kembali</a>

</div>