<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

//$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);
$query_buku = "SELECT * FROM koleksi";
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
    <title>Koleksi Buku</title>
    <style>
        h2 {
            font-weight: 600;
            text-align: center;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-attachment: fixed;
            background-size: cover;
            background-color: #e7ecff;
        }
         .navbar-nav .nav-link {
            margin-right: 15px;
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
         .modal-footer .btn-primary {
            background-color: #007bff;
            border: none;
        }
         .modal-footer .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
         .btn-primary:hover {
            background-color: #0056b3;
        }
         .btn-secondary:hover {
            background-color: #5a6268;
        }
         .btn-danger:hover {
            background-color: #c82333;
        }
        .container mt-5 {
            background-color: #cfe2ff;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body> 
    <nav class="navbar navbar-expand-lg" style ="background-color: #4460c3;" >
        <div class="container">
          <div>
            <a class="navbar-brand" href="#" style="color: white;">Pustaka Digital</a>
          </div>
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
            <h2>Koleksi Buku</h2>
            <!-- Tombol untuk membuka modal -->
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#tambahBuku">+ Tambah Koleksi</button>

            <!-- Modal -->
            <div class="modal fade" id="tambahBuku" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Koleksi Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <form action="tambah_buku.php" method="post">
                    <div class="modal-body">
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <label for="kode_buku" class="form-label">Kode Buku</label>
                          <input type="text" class="form-control" id="kode_buku" name="kode_buku" required>
                        </div>
                        <div class="col-md-6">
                          <label for="stok" class="form-label">Jumlah Stok</label>
                          <input type="number" class="form-control" id="stok" name="stok" required>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label for="judul_buku" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="judul_buku" name="judul_buku" required>
                      </div>

                      <div class="mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" id="pengarang" name="pengarang" required>
                      </div>

                      <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                          <option value="">Pilih Kategori</option>
                          <option value="Fiksi">Fiksi</option>
                          <option value="Non-Fiksi">Non-Fiksi</option>
                          <option value="Sains">Sains</option>
                          <option value="Sejarah">Sejarah</option>
                          <option value="Teknologi">Teknologi</option>
                        </select>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                      <button type="submit" class="btn btn-primary" name="submit">Tambah</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

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
                <?php while ($buku = mysqli_fetch_assoc($result_buku)) { ?> <!-- digunakan untuk menampilkan data buku -->
                <tr>
                    <td><?php echo $buku['id']; ?></td> 
                    <td><?php echo $buku['kode_buku']; ?></td> 
                    <td><?php echo $buku['judul_buku']; ?></td>
                    <td><?php echo $buku['pengarang']; ?></td>
                    <td><?php echo $buku['kategori']; ?></td>
                    <td><?php echo $buku['stok']; ?></td>
                <?php $status = ($buku['stok'] > 5) ? 'Tersedia' : (($buku['stok'] <= 5) ? 'Menipis' : 'Habis');
                    echo "<td>$status</td>";?>
                    <td>
                        <a href="edit_buku.php?id=<?php echo $buku['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#hapusbuku<?php echo $buku['id']; ?>">Hapus</button>
                          <div class="modal fade" id="hapusbuku<?php echo $buku['id']; ?>" tabindex="-1" aria-labelledby="hapusBukuLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Pustaka</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus buku ini?</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <a href="hapus_buku.php?id=<?php echo $buku['id']; ?>" class="btn btn-danger">Hapus</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                      </td>
                </tr>
                <?php } ?>
            </tbody>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>