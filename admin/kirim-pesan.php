<?php
session_start();
include 'koneksi.php';


$ids = $_GET['pesanId'];
$selectContact = mysqli_query($koneksi, "SELECT * FROM contact WHERE id = $ids");
$rowContact = mysqli_fetch_assoc($selectContact);

if (isset($_GET['pesanId'])) {
    $id = $_GET['pesanId'];
    $selectContact = mysqli_query($koneksi, "SELECT * FROM contact WHERE id = $id");
    $rowContact = mysqli_fetch_assoc($selectContact);
}


if (isset($_POST['kirim-bosss']) && isset($_GET['pesanId'])) {
    $id = $_GET['pesanId'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $balaspesan = $_POST['balaspesan'];

    $header = "From: fitrianurzh01@gmail.com" . "\r\n" .
                "Reply-To: fitrianurzh@gmail.com" . "\r\n" .
                "Content-Type: text/plain; charset=UTF8" ."\r\n" .
                "MIME-Version: 1.0" . "\r\n";
    
    if(mail($email, $subject, $balaspesan, $header)) {
        echo "Berhasil";
        header("Location: contact-admin.php?status=berhasil-terkirim");
        exit();
    } else {
        echo "Gagal";
        header("Location: kirim-pesan.php?status=gagal-terkirim");
    }
}

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
                                    <div class="card-header">Balas Pesan</div>
                                    <div class="card-body">
                                        <ul style="list-style-type: '-'">
                                            <li>
                                                <pre>Name : <?php echo $rowContact['nama'] ?></pre>
                                            </li>
                                            <li>
                                                <pre>Email : <?php echo $rowContact['email'] ?></pre>
                                            </li>
                                            <li>
                                                <pre>Subject : <?php echo $rowContact['subject'] ?></pre>
                                            </li>
                                            <li>
                                                <pre>Message : <?php echo $rowContact['message'] ?></pre>
                                            </li>
                                        </ul>
                                        <?php
                                        // if(isset($_GET['hapus'])): 
                                        ?>
                                        <!-- <div class="alert alert-success" role="alert">
                                                Data berhasil dihapus
                                            </div> -->


                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div>
                                                <input type="text" name="email" value="<?php echo $rowContact['email'] ?>">
                                            </div>
                                            <div class="mt-3">
                                                <label class="form-label" for="">Subject</label>
                                                <input type="text" class="form-control" name="subject" required>
                                            </div>
                                            <div class="mt-3">
                                                <label for="" class="form-label">Balas Pesan</label>
                                                <textarea class="form-control" name="balaspesan" cols="30" rows="10"></textarea>
                                            </div>
                                            <div class="mt-3">
                                                <button class="btn btn-primary" name="kirim-bosss">Kirim Pesan</button>
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