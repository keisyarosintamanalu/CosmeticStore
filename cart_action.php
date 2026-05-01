<?php
session_start();

$id = $_GET['id'];
$act = $_GET['act'];

if($act == "inc"){
    $_SESSION['cart'][$id]++;
}

if($act == "dec"){
    $_SESSION['cart'][$id]--;
    if($_SESSION['cart'][$id] <= 0){
        unset($_SESSION['cart'][$id]);
    }
}

if($act == "del"){
    unset($_SESSION['cart'][$id]);
}

header("Location: index.php");