<?php
session_start();
header('Content-Type: application/json');

$id = (int)$_POST['id'];
$action = $_POST['action'];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if($action == "inc"){
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
}

if($action == "dec"){
    $_SESSION['cart'][$id]--;
    if($_SESSION['cart'][$id] <= 0){
        unset($_SESSION['cart'][$id]);
    }
}

echo json_encode(['total_item'=>array_sum($_SESSION['cart'])]);