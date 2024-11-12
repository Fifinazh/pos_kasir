<?php
session_start();
require_once "../admin/koneksi.php";

if (isset($_POST['simpan'])) {
    $trans_code = $_POST['trans_code'];
    $trans_date = $_POST['trans_date'];
    $trans_total_price = $_POST['trans_total_price'];
    $trans_paid = $_POST['trans_paid'];
    $trans_change = $_POST['trans_change'];


    $querySales = mysqli_query($koneksi, "INSERT INTO sales (trans_code, trans_date, trans_total_price, trans_paid, trans_change) VALUES ('$trans_code', '$trans_date', '$trans_total_price', '$trans_paid', '$trans_change')");

    $sales_id = mysqli_insert_id($koneksi);

    foreach ($_POST['product_id'] as $key => $product_id) {
        $qty = $_POST['qty'][$key];
        $sub_total = $_POST['sub_total'][$key];


        $detailPenjualan = mysqli_query($koneksi, "INSERT INTO detail_sales (sales_id, product_id, qty, sub_total) VALUES ('$sales_id', '$product_id', '$qty', '$sub_total')");

        $updateQty = mysqli_query($koneksi, "UPDATE products SET product_qty = product_qty - $qty WHERE id = $product_id");
    }

    header("location: ../admin/print.php?id=" . $sales_id);
    exit();
}
