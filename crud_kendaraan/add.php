<?php
include 'db.php';

if (isset($_POST['submit'])) {
    // var_dump($_POST);die;
    $plat_nomor = trim($_POST['plat_nomor']);
    $jenis = trim($_POST['jenis_kendaraan']);
    $warna = trim($_POST['warna']);
    $pemilik = trim($_POST['pemilik']);
    $tanggal_servis = trim($_POST['tanggal_servis']);

    if (empty($plat_nomor) || empty($jenis)) {
        echo "<p style='color:red; text-align:center;'>Plat nomor dan jenis kendaraan wajib diisi!</p>";
    } else {
        $gambar = $_FILES['gambar']['name'];
        $target = 'uploads/' . basename($gambar);

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            $sql = "INSERT INTO kendaraan (plat_nomor, jenis_kendaraan, warna, pemilik, tanggal_servis, gambar)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssss", $plat_nomor, $jenis, $warna, $pemilik, $tanggal_servis, $gambar);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php');
                exit();
            } else {
                echo "<p style='color:red; text-align:center;'>Gagal menyimpan ke database: " . mysqli_error($conn) . "</p>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<p style='color:red; text-align:center;'>Upload gambar gagal!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Kendaraan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
            padding: 50px; 
        }
        form { 
            background: #fff; 
            padding: 30px; 
            border-radius: 10px; 
            max-width: 500px; 
            margin: auto; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="number"], input[type="date"], textarea, input[type="file"] {
            width: 100%; 
            padding: 10px; 
            margin: 8px 0; 
            border: 1px solid #ccc; 
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background: #007bff; 
            color: white; 
            padding: 10px 15px;
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover { 
            background: #0056b3; 
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Tambah Data Kendaraan</h2>
    <form action="add.php" method="POST" enctype="multipart/form-data">
        <label>Plat Nomor:</label>
        <input type="text" name="plat_nomor" placeholder="Misal: B 1234 XYZ" required>

        <label>Jenis Kendaraan:</label>
        <input type="text" name="jenis_kendaraan" placeholder="Mobil / Motor / Truk" required>
        <label>Warna:</label>
        <input type="text" name="warna" placeholder="Hitam / Putih / Merah">

        <label>Pemilik:</label>
        <input type="text" name="pemilik" placeholder="Nama pemilik kendaraan">

        <label>Tanggal Servis:</label>
        <input type="date" name="tanggal_servis">

        <label>Upload Gambar:</label>
        <input type="file" name="gambar" accept="image/*" required>

        <button type="submit" name="submit">Simpan Data Kendaraan</button>
    </form>
</body>
</html>
