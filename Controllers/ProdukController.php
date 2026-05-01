<?php
include_once dirname(__DIR__) . "/config/Database.php";
include_once dirname(__DIR__) . "/models/Produk.php";

class ProdukController {

    public $model;

    public function __construct() {
        $db = (new Database())->connect();
        $this->model = new Produk($db);
    }
}
?>