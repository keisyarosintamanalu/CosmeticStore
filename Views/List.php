<?php
$search = $_GET['search'] ?? "";
$data = $controller->model->getAll($search);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">

<h2 class="text-center mb-4">💄 Data Produk Kosmetik</h2>

<form method="GET" class="d-flex mb-3">
    <input type="text" name="search" class="form-control me-2" placeholder="Cari produk...">
    <button class="btn btn-primary">Cari</button>
</form>

<a href="views/tambah.php" class="btn btn-success mb-3">+ Tambah Produk</a>

<table class="table table-bordered text-center">
<tr class="table-dark">
    <th>No</th>
    <th>Gambar</th>
    <th>Nama Produk</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php 
$no = 1;
while($row = $data->fetch(PDO::FETCH_ASSOC)) {
?>

<tr>
    <td><?= $no++; ?></td>

    <td>
        <img src="uploads/<?= $row['gambar']; ?>" width="70">
    </td>

    <td><?= $row['nama_produk']; ?></td>
    <td>Rp <?= number_format($row['harga']); ?></td>
    <td><?= $row['stok']; ?></td>

    <td class="d-flex gap-2 justify-content-center">

        <a href="views/edit.php?id=<?= $row['id']; ?>" 
           class="btn btn-warning btn-sm">Edit</a>

        <a href="index.php?hapus=<?= $row['id']; ?>" 
           class="btn btn-danger btn-sm">Hapus</a>

        <a href="views/keranjang.php?tambah=<?= $row['id']; ?>" 
           class="btn btn-success btn-sm">
           + Keranjang
        </a>

    </td>
</tr>

<?php } ?>

</table>

<a href="views/dashboard.php" class="btn btn-info mt-3">
    📊 Lihat Grafik Penjualan
</a>

</div>