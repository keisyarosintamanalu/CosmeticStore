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

$jumlah_item = array_sum($_SESSION['cart']);

if(isset($_GET['tambah'])){
    $id = (int)$_GET['tambah'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: index.php");
    exit;
}

if(isset($_GET['like'])){
    $controller->model->like($_GET['like']);
    header("Location: index.php");
    exit;
}

if(isset($_POST['kirim_rating'])){
    $controller->model->rate($_POST['id'], (int)$_POST['nilai']);
    header("Location: index.php");
    exit;
}

if(isset($_GET['hapus'])){
    $controller->model->delete($_GET['hapus']);
    header("Location: index.php");
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-3 d-flex justify-content-between align-items-center">

<h5>Halo, <?= $_SESSION['username']; ?> 👋</h5>

<div class="d-flex gap-2 align-items-center">

<div class="dropdown">

<button class="btn btn-success dropdown-toggle position-relative" data-bs-toggle="dropdown">
🛒
<?php if($jumlah_item > 0): ?>
<span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
<?= $jumlah_item; ?>
</span>
<?php endif; ?>
</button>

<div class="dropdown-menu dropdown-menu-end p-3" style="width:320px">

<?php
$total_cart = 0;

if(empty($_SESSION['cart'])){
    echo "<p class='text-center'>Keranjang kosong</p>";
}else{

foreach($_SESSION['cart'] as $id=>$qty){

$item = $controller->model->getById($id);
if(!$item) continue;

$subtotal = $item['harga'] * $qty;
$total_cart += $subtotal;
?>

<div class="cart-item d-flex mb-2" data-id="<?= $item['id']; ?>">

<img src="uploads/<?= $item['gambar'] ?: 'default.png'; ?>"
style="width:50px;height:50px;object-fit:cover;border-radius:8px;margin-right:10px;">

<div style="flex:1">
<div class="small fw-bold"><?= $item['nama_produk']; ?></div>
<div class="small text-muted">
Rp <?= number_format($item['harga'],0,',','.'); ?>
</div>

<div class="d-flex mt-1">
<button class="btn btn-sm btn-outline-secondary btn-dec">-</button>
<span class="mx-2 qty"><?= $qty ?></span>
<button class="btn btn-sm btn-outline-secondary btn-inc">+</button>
</div>
</div>

<div class="small text-end subtotal">
Rp <?= number_format($subtotal,0,',','.'); ?>
</div>

</div>

<?php } ?>

<hr>

<b>Total: <span id="totalCart">
Rp <?= number_format($total_cart,0,',','.'); ?>
</span></b>

<a href="views/Keranjang.php" class="btn btn-success w-100 mt-2">
Lihat Keranjang
</a>

<?php } ?>

</div>
</div>

<a href="views/Dashboard.php" class="btn btn-info">📊 Dashboard</a>
<a href="logout.php" class="btn btn-danger">Logout</a>

</div>
</div>

<hr>

<?php include_once "views/List.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function rupiah(n){ return 'Rp ' + n.toLocaleString('id-ID'); }

document.addEventListener('click', function(e){

let item = e.target.closest('.cart-item');
if(!item) return;

let id = item.dataset.id;

if(e.target.classList.contains('btn-inc')){
fetch('cart_action.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:`action=inc&id=${id}`
})
.then(r=>r.json())
.then(data=>{
let qtyEl = item.querySelector('.qty');
qtyEl.innerText = parseInt(qtyEl.innerText)+1;
updateUI();
updateBadge(data.total_item);
});
}

if(e.target.classList.contains('btn-dec')){
fetch('cart_action.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:`action=dec&id=${id}`
})
.then(()=>location.reload());
}

});

function updateBadge(total){
let badge = document.querySelector('.badge');
if(badge) badge.innerText = total;
}

function updateUI(){
let total = 0;

document.querySelectorAll('.cart-item').forEach(item=>{
let price = item.querySelector('.text-muted').innerText
.replace('Rp','').replace(/\./g,'').trim();

let qty = parseInt(item.querySelector('.qty').innerText);
let sub = price * qty;

item.querySelector('.subtotal').innerText = rupiah(sub);
total += sub;
});

document.getElementById('totalCart').innerText = rupiah(total);
}
</script>