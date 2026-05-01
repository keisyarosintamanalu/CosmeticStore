<?php
include_once "../config/Database.php";

$db = (new Database())->connect();

$id = $_GET['id'];
$status = $_GET['status'];

$stmt = $db->prepare("UPDATE transaksi SET status=:s WHERE id=:id");
$stmt->execute([
    's'=>$status,
    'id'=>$id
]);