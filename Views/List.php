<?php
$search = $_GET['search'] ?? "";
$data = $controller->model->getAll($search);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#fff0f5; }
.product-card { border-radius:15px; transition:0.3s; }
.product-card:hover { transform:scale(1.03); }
.product-img { height:180px; object-fit:cover; }
.btn-pink { background:#ff69b4; color:white; }
</style>

<div class="container mt-4">

<h2 class="text-center text-danger">Cosmetics Store</h2>

<form method="GET" class="d-flex mb-3">
    <input type="text" name="search" class="form-control me-2" placeholder="Cari produk...">
    <button class="btn btn-pink">Cari</button>
</form>

<a href="views/tambah.php" class="btn btn-pink mb-3">+ Tambah Produk</a>

<div class="row">

<?php while($row = $data->fetch(PDO::FETCH_ASSOC)) { ?>

<div class="col-md-3 mb-4">
<div class="card product-card p-2 text-center">

<img src="uploads/<?= $row['gambar']; ?>" class="product-img">

<h6><?= $row['nama_produk']; ?></h6>

<p class="text-danger">
    Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
</p>

<span class="badge bg-success mb-2">
    Stok: <?= $row['stok']; ?>
</span>

<a href="index.php?like=<?= $row['id']; ?>" class="btn btn-outline-danger btn-sm">
    💖 <?= $row['likes']; ?>
</a>

<form method="POST" class="mt-1">
    <input type="hidden" name="id" value="<?= $row['id']; ?>">

    <select name="rating" class="form-select form-select-sm">
        <option value="5">⭐⭐⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="2">⭐⭐</option>
        <option value="1">⭐</option>
    </select>

    <button name="rating" class="btn btn-pink btn-sm mt-1">Kirim</button>
</form>

<p class="mt-1">⭐ <?= $row['rating']; ?></p>

<a href="index.php?tambah=<?= $row['id']; ?>" class="btn btn-success btn-sm">
   🛒 Tambah ke Keranjang
</a>

<a href="views/edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
    Edit
</a>

<a href="index.php?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm">
    Hapus
</a>

</div>
</div>

<?php } ?>

</div>

</div>