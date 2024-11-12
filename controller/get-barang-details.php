<?php
require_once "../admin/koneksi.php";

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $query = mysqli_query($koneksi, "SELECT product_qty, product_price FROM products WHERE id = '$product_id'");
    if (mysqli_num_rows($query) > 0) {
        $item = mysqli_fetch_assoc($query);

        header('Content-Type: application/json');
        echo json_encode($item);
    }
}
