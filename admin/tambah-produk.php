<?php
session_start();
include 'koneksi.php';

if (isset($_POST['simpan'])) {

    $product_name = $_POST['product_name'];
    $product_qty = $_POST['product_qty'];
    $product_price = $_POST['product_price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    $insert = mysqli_query($koneksi, "INSERT INTO products (product_name, product_qty, product_price, description, category_id ) VALUES ('$product_name','$product_qty','$product_price','$description','$category_id')");
    header("location:produk.php?tambah=berhasil");
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$editProd = mysqli_query(
    $koneksi,
    "SELECT * FROM products WHERE id = '$id'"
);
$rowEdit = mysqli_fetch_assoc($editProd);

if (isset($_POST['edit'])) {
    $product_name = $_POST['product_name'];
    $product_qty = $_POST['product_qty'];
    $product_price = $_POST['product_price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];


    //ubah user kolom apa yang mau di ubah (SET) 
    $update = mysqli_query($koneksi, "UPDATE products SET product_name= '$product_name', product_qty='$product_qty', product_price='$product_price', description='$description', category_id='$category_id' WHERE id='$id'");
    header("location:produk.php?ubah=berhasil");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM products WHERE id='$id'");
    header("location:produk.php?hapus=berhasil");
}

$queryCategories = mysqli_query($koneksi, "SELECT * FROM categories");

?>

<!DOCTYPE html>


<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <?php include 'inc/head.php' ?>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php include 'inc/sidebar.php' ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php include 'inc/nav.php' ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4><?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah' ?> Produk</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if (isset($_GET['hapus'])): ?>
                                            <div class="alert alert-success" role="alert">
                                                Data berhasil dihapus
                                            </div>
                                        <?php endif ?>

                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Nama Kategori</label>
                                                    <select name="category_id" id="" class="form-control">
                                                        <option value="">Pilih Kategori</option>
                                                        <!-- option yang datanya diambil dari tabel kategori -->
                                                        <?php while ($rowCategories = mysqli_fetch_assoc($queryCategories)): ?>
                                                            <option <?php echo isset($_GET['edit']) ? ($rowCategories['id'] == $rowEdit['category_id'] ? 'selected' : '') : '' ?> value="<?php echo $rowCategories['id'] ?>">
                                                                <?php echo $rowCategories['category_name'] ?></option>
                                                        <?php endwhile ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Nama Produk</label>
                                                    <input type="text" class="form-control" name="product_name" placeholder="Masukkan Nama Produk" required value="<?php echo isset($_GET['edit']) ? $rowEdit['product_name'] : '' ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Jumlah Produk</label>
                                                    <input type="number" class="form-control" name="product_qty" placeholder="Masukkan Nama Produk" required value="<?php echo isset($_GET['edit']) ? $rowEdit['product_qty'] : '' ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Harga Produk</label>
                                                    <input type="number" class="form-control" name="product_price" placeholder="Masukkan Harga Produk" required value="<?php echo isset($_GET['edit']) ? $rowEdit['product_price'] : '' ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Deskripsi</label>
                                                    <input type="text" class="form-control" name="description" placeholder="Masukkan Deskripsi Produk" required value="<?php echo isset($_GET['edit']) ? $rowEdit['description'] : '' ?>">
                                                </div>

                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-primary" name="<?php echo isset($_GET['edit']) ? 'edit' : 'simpan' ?>" type="submit">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include 'inc/footer.php' ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <?php include 'inc/js.php' ?>


</body>

</html>