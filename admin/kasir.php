<?php
include 'koneksi.php';
session_start();
session_regenerate_id(true);

$id = isset($_GET['id']) ? $_GET['id'] : '';
//mengambil data detail penjual dan penjualan
$queryKasir = mysqli_query($koneksi, "SELECT * FROM sales ORDER BY id DESC");

if (isset($_GET['delete'])) {
    $id = $_GET['delete']; //mengambil nilai param

    //query / perintah hapus
    $delete = mysqli_query($koneksi, "DELETE FROM sales WHERE id ='$id'");
    header("location:kasir.php?hapus=berhasil");
}
?>
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
                            <div class="col-2"></div>
                            <div class="col-8">
                                <div class="card mt-3">
                                    <div class="card-header text-center">
                                        <h1>Manage Kasir</h1>
                                    </div>
                                    <div class="card-body">
                                        <div class="table table-responsive">
                                            <div class="mt-2 mb-2">
                                                <a href="tambah-kasir.php" class="btn btn-primary btn-sm">Add</a>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Kode Transaksi</th>
                                                        <th>Tanggal Transaksi</th>
                                                        <th>Total Pembayaran</th>
                                                        <th>Nominal Pembayaran</th>
                                                        <th>Settings</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1;
                                                    while ($row = mysqli_fetch_assoc($queryKasir)) : ?>
                                                        <tr>
                                                            <td><?php echo $no++ ?></td>
                                                            <td><?php echo $row['trans_code'] ?></td>
                                                            <td><?php echo $row['trans_date'] ?></td>
                                                            <td><?php echo "Rp. " . number_format($row['trans_total_price']) ?></td>
                                                            <td><?php echo "Rp. " . number_format($row['trans_paid']) ?></td>
                                                            <td>
                                                                <a onclick="return confirm('Apakah anda yakin akan menghapus data ini??')" href="kasir.php?delete=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm">
                                                                    <span class="tf-icon bx bx-trash"></span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2"></div>

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