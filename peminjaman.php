<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit_pinjam'])) {
    $kode_pinjam      = $_POST['kode_pinjam'];
    $nama_peminjam    = $_POST['nama_peminjam'];
    $id_buku          = $_POST['id_buku'];         
    $tgl_pinjam       = $_POST['tgl_pinjam'];
    $tgl_kembali      = $_POST['tgl_kembali'];
    if (($tgl_kembali) < (date('Y-m-d'))) {
        $status_pinjam = 'Terlambat';
    } else {
        $status_pinjam = 'Dipinjam';
    }

    $q_buku = "SELECT judul_buku FROM koleksi WHERE id = '$id_buku'";
    $r_buku = mysqli_query($koneksi, $q_buku);
    $d_buku = mysqli_fetch_assoc($r_buku);
    $judul_buku = $d_buku['judul_buku'];

    $query = "INSERT INTO peminjaman (kode_pinjam, nama_peminjam, judul_buku, tgl_pinjam, tgl_kembali, status)
              VALUES ('$kode_pinjam', '$nama_peminjam', '$judul_buku', '$tgl_pinjam', '$tgl_kembali', '$status_pinjam')";

    if (mysqli_query($koneksi, $query)) {
        $update = "UPDATE koleksi SET stok = stok - 1 WHERE id = '$id_buku'";
        mysqli_query($koneksi, $update);

        $q_status = "UPDATE koleksi SET status = 
                        CASE 
                            WHEN stok = 0 THEN 'Habis'
                            WHEN stok <= 5 THEN 'Menipis'
                            ELSE 'Tersedia'
                        END
                     WHERE id = '$id_buku'";
        mysqli_query($koneksi, $q_status);

        header("Location: peminjaman.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

if (isset($_GET['kembalikan'])) {
    $id_pinjam = $_GET['kembalikan'];

    $q_p = "SELECT judul_buku FROM peminjaman WHERE id = '$id_pinjam'";
    $r_p = mysqli_query($koneksi, $q_p);
    $d_p = mysqli_fetch_assoc($r_p);
    $judul_buku = $d_p['judul_buku'];

    $q_up = "UPDATE peminjaman SET status = 'Dikembalikan' WHERE id = '$id_pinjam'";
    mysqli_query($koneksi, $q_up);

    $q_stok = "UPDATE koleksi SET stok = stok + 1 WHERE judul_buku = '$judul_buku'";
    mysqli_query($koneksi, $q_stok);

    $q_status = "UPDATE koleksi SET status = 
                    CASE 
                        WHEN stok = 0 THEN 'Habis'
                        WHEN stok <= 5 THEN 'Menipis'
                        ELSE 'Tersedia'
                    END
                 WHERE judul_buku = '$judul_buku'";
    mysqli_query($koneksi, $q_status);

    header("Location: peminjaman.php");
    exit();
}

$query_pinjam = "SELECT * FROM peminjaman";
$result_pinjam = mysqli_query($koneksi, $query_pinjam);

// Buku yang stoknya > 0 (tersedia untuk dipinjam) kemudin dirutkan seuai dengan abjad
$query_buku = "SELECT id, judul_buku, stok FROM koleksi WHERE stok > 0 ORDER BY judul_buku ASC";
$result_buku = mysqli_query($koneksi, $query_buku);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Peminjaman</title>
    <style>
        h2 {
            font-weight: 600;
            text-align: center;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e7ecff;
        }
        .table {
            margin-top: 20px;
            box-shadow: grey 0px 4px 8px;
        }
        .table thead th {
            background-color: #cfe2ff;
        }
        .table tbody tr:hover {
            background-color: #cfe2ff;
        }
        .modal-header {
            background-color: #ffffff;
            color: black;
        }
        .btn-primary:hover  { background-color: #0056b3; }
        .btn-secondary:hover{ background-color: #5a6268; }
        .btn-danger:hover   { background-color: #c82333; }

        .badge-dipinjam     { color: #000; }
        .badge-dikembalikan { color: #000; }
        .badge-terlambat    { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="background-color: #4460c3;">
        <div class="container">
            <a class="navbar-brand" href="#" style="color: white;">Pustaka Digital</a>
            <div class="me-3">
                <a href="koleksi_buku.php" class="btn" style="color: white;">Koleksi Buku</a>
            </div>
            <div class="me-3">
                <a href="peminjaman.php" class="btn" style="color: white;">Peminjaman</a>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="btn btn-light" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="w-100">Database Peminjaman</h2>
        </div>

        <div class="d-flex justify-content-end mb-2">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalCatat">
                <i class="bi bi-journal-plus"></i> Catat Peminjaman
            </button>
        </div>

        <div class="modal fade" id="modalCatat" tabindex="-1" aria-labelledby="modalCatatLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCatatLabel">Form Data Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="peminjaman.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="kode_pinjam" class="form-label">Kode Peminjam</label>
                                <input type="text" class="form-control" id="kode_pinjam" name="kode_pinjam" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                                <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam" required>
                            </div>

                            <div class="mb-3">
                                <label for="id_buku" class="form-label">Pilih Buku</label>
                                <select class="form-select" id="id_buku" name="id_buku" required>
                                    <option value="">Pilih Buku Tersedia</option>
                                    <?php while ($buku = mysqli_fetch_assoc($result_buku)) { ?>
                                        <option value="<?php echo $buku['id']; ?>">
                                            <?php echo $buku['judul_buku'] . ' - Stok: ' . $buku['stok']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                                    <input type="date" class="form-control" id="tgl_pinjam" name="tgl_pinjam" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_kembali" class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" id="tgl_kembali" name="tgl_kembali" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary" name="submit_pinjam">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Peminjaman</th>
                    <th>Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($pinjam = mysqli_fetch_assoc($result_pinjam)) {
                    $status = $pinjam['status'];
                    if ($status == 'Dipinjam') {
                        $badge = 'badge-dipinjam';
                    } elseif ($status == 'Dikembalikan') {
                        $badge = 'badge-dikembalikan';
                    } else {
                        $badge = 'badge-terlambat';
                    }
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $pinjam['kode_pinjam']; ?></td>
                    <td><?php echo $pinjam['nama_peminjam']; ?></td>
                    <td><?php echo $pinjam['judul_buku']; ?></td>
                    <td><?php echo $pinjam['tgl_pinjam']; ?></td>
                    <td><?php echo $pinjam['tgl_kembali']; ?></td>
                    <td>
                        <span class="badge <?php echo $badge; ?> px-2 py-1 rounded">
                            <?php echo $status; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($status == 'Dikembalikan') { ?>
                            <button class="btn btn-success btn-sm" disabled>Selesai</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-info btn-sm text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#konfirmasiKembali<?php echo $pinjam['id']; ?>">
                                Kembalikan
                            </button>

                            <div class="modal fade" id="konfirmasiKembali<?php echo $pinjam['id']; ?>"
                                 tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin mengkonfirmasi pengembalian buku
                                               <strong><?php echo $pinjam['judul_buku']; ?></strong>
                                               oleh <strong><?php echo $pinjam['nama_peminjam']; ?></strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <a href="peminjaman.php?kembalikan=<?php echo $pinjam['id']; ?>"
                                               class="btn btn-primary">Ya, Kembalikan</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </td>
                </tr>
                <?php } ?> 
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>