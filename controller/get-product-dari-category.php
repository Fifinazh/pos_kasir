<?php
include "../admin/koneksi.php";
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $kategori = mysqli_query($koneksi, "SELECT * FROM products WHERE category_id = '$category_id'");

    $items = [];
    if (mysqli_num_rows($kategori) > 0) {
        while ($row = mysqli_fetch_assoc($kategori)) {
            $items[] = $row;
        }
    }
    //kembalikan hasil dalam bentuk json
    header('Content-Type: application/json');
    echo json_encode($items);
}
