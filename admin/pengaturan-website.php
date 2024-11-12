<?php
session_start();
include 'koneksi.php';


//jika button simpan di klik
$queryPengaturan = mysqli_query($koneksi, "SELECT * FROM general_setting ORDER BY id DESC");
$rowPengaturan = mysqli_fetch_assoc($queryPengaturan);
if (isset($_POST['simpan'])) {
    $website_name = $_POST['website_name'];
    $website_link = $_POST['website_link'];
    $id = $_POST['id'];
    $website_phone = $_POST['website_phone'];
    $website_email = $_POST['website_email'];
    $website_adress = $_POST['website_adress'];
    
    //mencari data di dalam table pengaturan, jika ada data akan di update, jika tidak ada akan di insert

    if (mysqli_num_rows($queryPengaturan) > 0) {
        if (!empty($_FILES['foto']['name'])) {
            $nama_foto = $_FILES['foto']['name'];
            $ukuran_foto = $_FILES['foto']['size'];

            //kita bikin tipe foto: png, jpg, jpeg
            $ext = array('png', 'jpg', 'jpeg', 'jfif');
            $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);

            //JIKA EXTENSI FOTO TIDAK EXT YANG TERDAFTAR DI ARRAY EXT
            if (!in_array($extFoto, $ext)) {
                echo "Maaf, foto tidak dapat diupload karena format tidak sesuai";
                die;
            } else {
                //pindahkan gambar dari tmp folder ke folder yg sudah kita buat
                // unlink() : mendelete file
                unlink('upload/' . $rowPengaturan['logo']);
                move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);

                $update = mysqli_query($koneksi, "UPDATE general_setting SET website_name='$website_name', website_link='$website_link', logo='$nama_foto', website_phone='$website_phone', website_email='$website_email', website_adress='$website_adress' WHERE id = '$id'");
            }
        } else {
            $update = mysqli_query($koneksi, "UPDATE general_setting SET website_name='$website_name', website_link='$website_link', website_phone='$website_phone', website_email='$website_email', website_adress='$website_adress' WHERE id = '$id'");
        }
    } else {
        if (!empty($_FILES['foto']['name'])) {
            $nama_foto = $_FILES['foto']['name'];
            $ukuran_foto = $_FILES['foto']['size'];

            //kita bikin tipe foto: png, jpg, jpeg
            $ext = array('png', 'jpg', 'jpeg');
            $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);

            //JIKA EXTENSI FOTO TIDAK EXT YANG TERDAFTAR DI ARRAY EXT
            if (!in_array($extFoto, $ext)) {
                echo "Maaf, foto tidak dapat diupload karena format tidak sesuai";
                die;
            } else {
                //pindahkan gambar dari tmp folder ke folder yg sudah kita buat
                move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);

                $insert = mysqli_query($koneksi, "INSERT INTO general_setting (website_name, website_link, logo) VALUES ('$website_name','$website_link','$nama_foto')");
            }
        } else {
            $insert = mysqli_query($koneksi, "INSERT INTO general_setting (website_name, website_link) VALUES ('$website_name','$website_link')");
        }
    }

    //$_POST: form input name=''
    //$_GET: url ?param='nilai'
    //$_FILES: ngambil nilai dari input type file



    header("location:pengaturan-website.php");
}



// $id = isset($_GET['edit']) ? $_GET['edit'] : '';
// $queryEdit = mysqli_query($koneksi, "SELECT * FROM user WHERE id='$id'");
// $rowEdit = mysqli_fetch_assoc($queryEdit);

//jika button edit di klik
if (isset($_POST['edit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    //jika password di isi sama user
    if ($_POST['password']) {
        $password = $_POST['password'];
    } else {
        $password = $rowEdit['password'];
    }

    $update = mysqli_query($koneksi, "UPDATE user SET nama='$nama',email='$email', password='$password' WHERE id='$id'");
    header("location:user.php?ubah=berhasil");
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
                                    <h3 class="card-header">Pengaturan Website</h3>
                                    <div class="card-body">
                                        <?php if (isset($_GET['hapus'])): ?>
                                            <div class="alert alert-success" role="alert">
                                                Data berhasil dihapus
                                            </div>
                                        <?php endif ?>

                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" class="form-control" name="id" value="<?php echo ($rowPengaturan['id']) ? $rowPengaturan['id'] : '' ?>">

                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Nama Website</label>
                                                        <input type="text" class="form-control" name="website_name" placeholder="Masukkan Nama Website" required value="<?php echo ($rowPengaturan['website_name']) ? $rowPengaturan['website_name'] : '' ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Telepon</label>
                                                        <input type="text" class="form-control" name="website_phone" placeholder="Masukkan Telepon Website" required value="<?php echo ($rowPengaturan['website_phone']) ? $rowPengaturan['website_phone'] : '' ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Link Website</label>
                                                        <input type="url" class="form-control" name="website_link" placeholder="Masukkan Link Website Anda" required value="<?php echo ($rowPengaturan['website_link']) ? $rowPengaturan['website_link'] : '' ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="" class="form-label">Email Website</label>
                                                        <input type="url" class="form-control" name="website_email" placeholder="Masukkan Email Website Anda" required value="<?php echo ($rowPengaturan['website_email']) ? $rowPengaturan['website_email'] : '' ?>">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="mb-3 row">
                                                <div class="col-sm-12">
                                                    <label for="" class="form-label">Alamat</label>
                                                    <input type="" class="form-control" name="website_adress" placeholder="Masukkan Alamat Anda" value="<?php echo ($rowPengaturan['website_adress']) ? $rowPengaturan['website_adress'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <div class="col-sm-12">
                                                    <label for="" class="form-label">Foto</label>
                                                    <input type="file" name="foto">
                                                    <img width="200" src="upload/<?php echo isset($rowPengaturan['logo']) ? $rowPengaturan['logo'] : '' ?>" alt="">
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