<?php
include "koneksi.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';
//mengambil data detail penjual dan penjualan
$queryDetail = mysqli_query($koneksi, "SELECT sales.id, sales.trans_total_price, sales.trans_paid, sales.trans_change, products.product_name, products.product_price, detail_sales.* FROM detail_sales LEFT JOIN sales ON sales.id = detail_sales.sales_id LEFT JOIN products ON products.id = detail_sales.product_id WHERE detail_sales.sales_id= '$id'");

$row = [];
while ($rowDetail = mysqli_fetch_assoc($queryDetail)) {
    $row[] = $rowDetail;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Transaksi : </title>
    <style>
        body {
            margin: 20px;
        }

        .struk {
            width: 80mm;
            max-width: 100%;
            border: 1px solid #000;
            padding: 10px;
            margin: 0 auto;
        }

        .struk-header,
        .struk-footer {
            text-align: center;
            margin-bottom: 10px;
        }

        .struk-header h1 {
            font-size: 18px;
            margin: 0;
        }

        .struk-body {
            margin-bottom: 10px;
        }

        .struk-body table {
            border-collapse: collapse;
            width: 100%;
        }

        .struk-body table th,
        .struk-body table td {
            padding: 5px;
            text-align: left;
        }

        .struk-body table th {
            border-bottom: 1px solid #000;
        }

        .total,
        .payment,
        .change {
            display: flex;
            justify-content: space-evenly;
            padding: 5px 0;
            font-weight: bold;
        }

        .total {
            margin-top: 10px;
            border-top: 1px solid #000;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .struk {
                width: auto;
                border: none;
                margin: 0;
                padding: 0;
            }

            .struk-header h1,
            .struk-footer {
                font-size: 14px;
            }

            .struk-body table th,
            .struk-body table td {
                padding: 2px;
            }

            .total,
            .payment,
            .change {
                padding: 2px 0;
            }
        }
    </style>
</head>

<body>

    <div class="struk">
        <div class="struk-header">
            <h1>Moe Mart</h1>
            <p>Kemayoran, Jakarta Pusat</p>
            <p>081245367695</p>
        </div>
        <div class="struk-body">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah Barang</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- row : [0] => data-->
                    <?php foreach ($row as $key => $rowDetail): ?>
                        <tr>
                            <td><?php echo $rowDetail['product_name'] ?></td>
                            <td><?php echo $rowDetail['qty'] ?></td>
                            <td><?php echo "Rp. " . number_format($rowDetail['product_price']) ?></td>
                            <td><?php echo "Rp. " . number_format($rowDetail['sub_total']) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <div class="total">
                <span>Total :</span>
                <span><?php echo "Rp." . number_format($row[0]['trans_total_price']) ?></span>
            </div>
            <div class="payment">
                <span>Bayar :</span>
                <span><?php echo "Rp." . number_format($row[0]['trans_paid']) ?></span>
            </div>
            <div class="change">
                <span>Kembalian :</span>
                <span><?php echo "Rp." . number_format($row[0]['trans_change']) ?></span>
            </div>
        </div>
        <div class="struk-footer">
            <p>Terima Kasih Atas Kunjungan Anda!</p>
            <p>Selamat Berbelanja Kembali</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>