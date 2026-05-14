<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
} 
    if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM koleksi WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="#">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        <input type="text" name="Kode Buku" value="<?php echo $data['kode_buku']; ?>" required>
        <input type="text" name="judul" value="<?php echo $data['judul_buku']; ?>" required>
        <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $data['stok']; ?>" required>
        <input type="text" name="pengarang" value="<?php echo $data['pengarang']; ?>" required>
        <input type="text" name="penerbit" value="<?php echo $data['penerbit']; ?>" required>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>