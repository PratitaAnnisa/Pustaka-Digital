<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pustaka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #4460c3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 400px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #e7ecff;
        }
        h2 {
            font-weight: 600;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title text-center">Pustaka Digital</h2>
            <p class="card-text text-center">Sistem Perpustakan Nasional</p>
            <form method="POST" action="">
                    <?php
                if (isset($result) && mysqli_num_rows($result) > 0) {
                    $_SESSION['login'] = true;
                    header("Location: koleksi_buku.php");
                    exit();
                } else if (isset($result)) {
                    echo "<div class='alert alert-danger mt-3 text-center' role='alert'>Username atau Password salah!</div>";
                } ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label> <!-- username: pratita -->
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label> <!-- password: 124250014 -->
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>