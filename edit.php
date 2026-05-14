<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// Ambil data buku berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM koleksi WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
} else {
    header("Location: koleksi_buku.php");
    exit();
}

// Proses simpan perubahan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id        = $_POST['id'];
    $kode_buku = $_POST['kode_buku'];
    $judul_buku= $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $kategori  = $_POST['kategori'];
    $stok      = $_POST['stok'];
    $status    = ($stok == 0) ? 'Habis' : (($stok <= 5) ? 'Menipis' : 'Tersedia');

    $query = "UPDATE koleksi SET 
                kode_buku='$kode_buku', 
                judul_buku='$judul_buku', 
                pengarang='$pengarang', 
                kategori='$kategori', 
                stok='$stok', 
                status='$status'
              WHERE id='$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: koleksi_buku.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
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
    <title>Edit Buku</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e7ecff;
        }
        .form-card {
            max-width: 600px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <!-- Navbar sama seperti halaman lain -->
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

    <div class="container">
        <div class="form-card">
            <h2>Form Edit Buku</h2>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                <!-- ID Buku: read-only sesuai ketentuan soal -->
                <div class="mb-3">
                    <label class="form-label">ID Buku</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $data['id']; ?>" readonly>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_buku" class="form-label">Kode Buku</label>
                        <input type="text" class="form-control" id="kode_buku" name="kode_buku"
                               value="<?php echo $data['kode_buku']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="stok" class="form-label">Jumlah Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0"
                               value="<?php echo $data['stok']; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="judul_buku" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul_buku" name="judul_buku"
                           value="<?php echo $data['judul_buku']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang"
                           value="<?php echo $data['pengarang']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Fiksi"     <?php if($data['kategori']=='Fiksi')     echo 'selected'; ?>>Fiksi</option>
                        <option value="Non-Fiksi" <?php if($data['kategori']=='Non-Fiksi') echo 'selected'; ?>>Non-Fiksi</option>
                        <option value="Sains"     <?php if($data['kategori']=='Sains')     echo 'selected'; ?>>Sains</option>
                        <option value="Sejarah"   <?php if($data['kategori']=='Sejarah')   echo 'selected'; ?>>Sejarah</option>
                        <option value="Teknologi" <?php if($data['kategori']=='Teknologi') echo 'selected'; ?>>Teknologi</option>
                    </select>
                </div>

                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="koleksi_buku.php" class="btn btn-secondary px-4">Kembali</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>