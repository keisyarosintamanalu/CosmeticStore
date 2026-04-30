<?php
session_start();

include_once "controllers/ProdukController.php";
$controller = new ProdukController();

if(!isset($_SESSION['login'])){
    header("Location: views/Login.php");
    exit;
}

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

$jumlah_item = 0;
foreach($_SESSION['cart'] as $q){ $jumlah_item += $q; }

if(isset($_POST['ajax'])){
    header('Content-Type: application/json');

    $action = $_POST['action'];
    $id = (int)$_POST['id'];

    if($action == "inc"){
        $_SESSION['cart'][$id]++;
    }

    if($action == "dec"){
        $_SESSION['cart'][$id]--;
        if($_SESSION['cart'][$id] <= 0){
            unset($_SESSION['cart'][$id]);
        }
    }

    if($action == "remove"){
        unset($_SESSION['cart'][$id]);
    }

    $jumlah_item = 0;
    foreach($_SESSION['cart'] as $q){ $jumlah_item += $q; }

    echo json_encode(['count'=>$jumlah_item]);
    exit;
}

$cart_items = [];
$total_cart = 0;

foreach($_SESSION['cart'] as $id => $qty){
    $item = $controller->model->getById($id);

    if($item){
        $item['qty'] = $qty;
        $item['subtotal'] = $item['harga'] * $qty;
        $total_cart += $item['subtotal'];
        $cart_items[] = $item;
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-light bg-light shadow-sm">
<div class="container">

<a class="navbar-brand text-danger fw-bold" href="index.php">
Cosmetics Store
</a>

<div class="d-flex align-items-center gap-2">

<span>Halo, <?= $_SESSION['username']; ?> 👋</span>

<div class="dropdown">

<button class="btn btn-outline-danger dropdown-toggle position-relative" data-bs-toggle="dropdown">
🛒
<?php if($jumlah_item > 0): ?>
<span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
<?= $jumlah_item; ?>
</span>
<?php endif; ?>
</button>

<div class="dropdown-menu dropdown-menu-end p-3" style="width:320px;">

<?php if(empty($cart_items)): ?>
<p class="text-center">Keranjang kosong</p>
<?php else: ?>

<?php foreach($cart_items as $item): ?>
<div class="cart-item d-flex mb-2" data-id="<?= $item['id']; ?>">

<!-- 🖼️ GAMBAR -->
<img src="uploads/<?= $item['gambar'] ?: 'default.png'; ?>"
style="width:50px;height:50px;object-fit:cover;border-radius:8px;margin-right:10px;">

<div style="flex:1">
<div class="fw-bold small"><?= $item['nama_produk']; ?></div>
<div class="small text-muted">
Rp <?= number_format($item['harga'],0,',','.'); ?>
</div>

<div class="mt-1 d-flex gap-1">
<button class="btn btn-sm btn-outline-secondary btn-dec">-</button>
<span class="qty"><?= $item['qty']; ?></span>
<button class="btn btn-sm btn-outline-secondary btn-inc">+</button>
</div>
</div>

<div class="text-end">
<div class="subtotal">
Rp <?= number_format($item['subtotal'],0,',','.'); ?>
</div>
<button class="btn btn-sm text-danger btn-remove">Hapus</button>
</div>

</div>
<hr>
<?php endforeach; ?>

<h6>Total: Rp <?= number_format($total_cart,0,',','.'); ?></h6>

<a href="views/Keranjang.php" class="btn btn-success w-100">
Lihat Keranjang
</a>

<?php endif; ?>

</div>
</div>

<a href="views/Dashboard.php" class="btn btn-info">Dashboard</a>
<a href="logout.php" class="btn btn-danger">Logout</a>

</div>
</div>
</nav>

<hr>

<?php include_once "views/List.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function rupiah(n){
return 'Rp ' + n.toLocaleString('id-ID');
}

document.addEventListener('click', function(e){

let item = e.target.closest('.cart-item');
if(!item) return;

let id = item.dataset.id;

if(e.target.classList.contains('btn-inc')){
fetch('index.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:`ajax=1&action=inc&id=${id}`
})
.then(r=>r.json())
.then(()=>{
let qtyEl = item.querySelector('.qty');
let qty = parseInt(qtyEl.innerText)+1;
qtyEl.innerText = qty;
location.reload();
});
}

if(e.target.classList.contains('btn-dec')){
fetch('index.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:`ajax=1&action=dec&id=${id}`
})
.then(r=>r.json())
.then(()=>{
location.reload();
});
}

if(e.target.classList.contains('btn-remove')){
fetch('index.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:`ajax=1&action=remove&id=${id}`
})
.then(r=>r.json())
.then(()=>{
location.reload();
});
}

});
</script>