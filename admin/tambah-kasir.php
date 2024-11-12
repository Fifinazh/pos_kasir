<?php
session_start();
session_regenerate_id();
date_default_timezone_set("Asia/Jakarta");
include "koneksi.php";

//waktu
$currentTime = date('Y-m-d');

//generateTransactionCode()
function generateTransactionCode()
{
    $kode = date('ymdHis');

    return $kode;
}
//click_count
if (empty($_SESSION['click_count'])) {
    $_SESSION['click_count'] = 0;
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
                            <div class="col-1"></div>
                            <div class="col-10">
                                <form action="../controller/transaksi-store.php" method="post">
                                    <div class="mb-1">
                                        <label class="form-label" for="">Kode Transaksi</label>
                                        <input class="form-control w-50" name="trans_code" id="trans_code" type="text" value="<?php echo "TR-" . generateTransactionCode() ?>" readOnly>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="">Tanggal Transaksi</label>
                                        <input class="form-control w-50" name="trans_date" id="trans_date" type="date" value="<?php echo $currentTime ?>" readOnly>
                                    </div>
                                    <div class="mb-1">
                                        <button class="btn btn-primary btn-sm" type="button" id="counterBtn">Tambah</button>
                                    </div>
                                    <div class="table-table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama Kategori</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah Barang</th>
                                                    <th>Sisa Produk</th>
                                                    <th>Harga</th>
                                                    <th>Sub Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody">
                                                <!-- data ditambah disini -->
                                            </tbody>
                                            <tfoot class="text-center">
                                                <tr>
                                                    <th colspan="6">Total Harga</th>
                                                    <td><input type="number" id="total_harga_keseluruhan" name="trans_total_price" class="form-control" readonly></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="6">Nominal Bayar</th>
                                                    <td><input type="number" id="nominal_bayar_keseluruhan" name="trans_paid" class="form-control" required></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="6">Kembalian</th>
                                                    <td><input type="number" id="kembalian_keseluruhan" name="trans_change" class="form-control" readonly></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="mb-3">
                                        <input type="submit" class="btn btn-primary" name="simpan" value="Cetak Struk">
                                        <a href="kasir.php" class="btn btn-danger">Kembali</a>
                                    </div>
                                </form>
                            </div>
                            <div class=" col-1"></div>
                        </div>
                    </div>
                    <!-- / Content -->
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM categories");
                    $categories = mysqli_fetch_all($query, MYSQLI_ASSOC);
                    ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            //fungsi tambah baris
                            const button = document.getElementById('counterBtn');
                            const countDisplay = document.getElementById('countDisplay');
                            const tbody = document.getElementById('tbody');
                            let no = 0;

                            button.addEventListener('click', function() {
                                no++

                                //fungsi tambah td
                                let newRow = "<tr>";
                                newRow += "<td>" + no + "</td>";
                                newRow += "<td><select class='form-control category-select' name='category_id[]' required>"
                                newRow += "<option value=''>--Pilih Kategori--</option>";
                                <?php foreach ($categories as $category) { ?>
                                    newRow += "<option value='<?php echo $category['id'] ?>'><?php echo $category['category_name'] ?></option>"
                                <?php
                                } ?>
                                newRow += "</select></td>";
                                newRow += "<td> <select class='form-control item-select' name='product_id[]' required>";
                                newRow += "<option value=''>--Pilih Barang--</option>";
                                newRow += "</select></td>";
                                newRow += "<td><input type='number' name='qty[]' class='form-control jumlah-input' value='0' required></td>";
                                newRow += "<td><input type='number' name='sisa_produk[]' class='form-control' readonly></td>";
                                newRow += "<td><input type='number' name='harga[]' class='form-control' readonly></td>";
                                newRow += "<td><input type='number' name='sub_total[]' class='form-control sub-total' readonly></td>";
                                newRow += "</tr>";
                                tbody.insertAdjacentHTML('beforeend', newRow);

                                attachCategoryChangeListener();
                                attachItemChangeListener();
                                attachJumlahChangeListener();

                            });

                            //fungsi untuk menampilkan barang berdasarkan kategori...
                            function attachCategoryChangeListener() {
                                const categorySelects = document.querySelectorAll('.category-select');
                                categorySelects.forEach(select => {

                                    select.addEventListener('change', function() {
                                        const categoryId = this.value;
                                        const itemSelect = this.closest('tr').querySelector('.item-select');

                                        if (categoryId) {
                                            fetch(`../controller/get-product-dari-category.php?category_id=${categoryId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    itemSelect.innerHTML = "<option value=''>--Pilih Barang--</option>";
                                                    data.forEach(item => {
                                                        itemSelect.innerHTML += `<option value='${item.id}'>${item.product_name}</option>`;
                                                    });
                                                });
                                        } else {
                                            itemSelect.innerHTML = "<option value=''>--Pilih Barang--</option>";
                                        }
                                    });
                                });
                            }

                            //untuk menampilkan qty dan harga...
                            function attachItemChangeListener() {
                                const itemSelects = document.querySelectorAll('.item-select');
                                itemSelects.forEach(select => {
                                    select.addEventListener('change', function() {
                                        const itemId = this.value;
                                        console.log(itemSelects);
                                        const row = this.closest('tr');
                                        const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]');
                                        const hargaInput = row.querySelector('input[name="harga[]"]');

                                        if (itemId) {
                                            fetch(`../controller/get-barang-details.php?product_id=${itemId}`)

                                                .then(response => response.json())
                                                .then(data => {
                                                    sisaProdukInput.value = data.product_qty;
                                                    hargaInput.value = data.product_price;
                                                })
                                        } else {
                                            sisaProdukInput.value = '';
                                            hargaInput.value = '';
                                        }
                                    });
                                });
                            }
                            const totalHargaKeseluruhan = document.getElementById('total_harga_keseluruhan');
                            const nominalBayarKeseluruhanInput = document.getElementById('nominal_bayar_keseluruhan');
                            const kembalianKeseluruhanInput = document.getElementById('kembalian_keseluruhan');
                            //fungsi untuk membuat alert jumlah > sisaProduk
                            function attachJumlahChangeListener() {
                                const jumlahInputs = document.querySelectorAll('.jumlah-input');
                                jumlahInputs.forEach(input => {
                                    input.addEventListener('input', function() {
                                        const row = this.closest('tr');
                                        const sisaProdukInput = row.querySelector('input[name="sisa_produk[]"]');
                                        const hargaInput = row.querySelector('input[name="harga[]"]');
                                        const totalHargaInput = document.getElementById('total_harga_keseluruhan');
                                        const totalBayarInput = document.getElementById('nominal_bayar_keseluruhan');
                                        const totalKembalianInput = document.getElementById('kembalian_keseluruhan');

                                        const jumlah = parseInt(this.value) || 0;
                                        const sisaProduk = parseInt(sisaProdukInput.value) || 0;
                                        const harga = parseFloat(hargaInput.value) || 0;

                                        if (jumlah > sisaProduk) {
                                            alert("Jumlah tidak boleh melebihi sisa produk");
                                            this.value = sisaProduk;
                                            return;
                                        }
                                        updateTotalKeseluruhan();
                                    });
                                });
                            }

                            function updateTotalKeseluruhan() {
                                let total = 0;
                                let totalKeseluruhan = 0;
                                const jumlahInput = document.querySelectorAll('.jumlah-input');
                                jumlahInput.forEach(input => {
                                    const row = input.closest('tr');
                                    const hargaInput = row.querySelector('input[name="harga[]"]');
                                    const harga = parseFloat(hargaInput.value) || 0;
                                    const jumlah = parseInt(input.value) || 0;

                                    const subTotal = row.querySelector('.sub-total');
                                    total = jumlah * harga;
                                    subTotal.value = total;
                                });
                                const subTotal = document.querySelectorAll('.sub-total');
                                subTotal.forEach(totalitas => {
                                    let subTotal = parseFloat(totalitas.value) || 0;
                                    totalKeseluruhan += subTotal;
                                })

                                totalHargaKeseluruhan.value = totalKeseluruhan;
                            }
                            nominalBayarKeseluruhanInput.addEventListener('input', function() {
                                const nominalBayar = parseFloat(this.value) || 0;
                                const totalHarga = parseFloat(totalHargaKeseluruhan.value) || 0;

                                if (nominalBayar >= totalHarga) {
                                    let kembalian = nominalBayar - totalHarga;
                                    kembalianKeseluruhanInput.value = kembalian;
                                } else if (nominalBayar == 0) {
                                    kembalianKeseluruhanInputInput.value = 0;

                                }
                            });
                        });
                    </script>

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