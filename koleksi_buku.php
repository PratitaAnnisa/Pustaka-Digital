<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);
$query_buku = "SELECT * FROM buku";
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
    <title>Koleksi Buku</title>
</head>
<body style="background-color: blue;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Pustaka Digital</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Koleksi Buku</h2>
            <a href="tambah_buku.php" class="btn btn-primary">Tambah Buku</a>
        </div>
        <table class="table table-bordered table-stripped text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Buku</th>
                    <th>Judul</th>
                    <th>pengarang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($buku = mysqli_fetch_assoc($result_buku)) { ?>
                <tr>
                    <td><?php echo $buku['id']; ?></td>
                    <td><?php echo $buku['kode_buku']; ?></td> 
                    <td><?php echo $buku['judul']; ?></td>
                    <td><?php echo $buku['penulis']; ?></td>
                    <td><?php echo $buku['kategori']; ?></td>
                    <td><?php echo $buku['stok']; ?></td>
                    <td><?php echo $buku['status']; ?></td>
                    <td>
                        <a href="edit_buku.php?id=<?php echo $buku['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="hapus_buku.php?id=<?php echo $buku['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

    </div>
</body>
</html>