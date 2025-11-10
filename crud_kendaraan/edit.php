<?php
include 'db.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM kendaraan WHERE id='$id'");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $plat_nomor = trim($_POST['plat_nomor']);
    $jenis = trim($_POST['jenis_kendaraan']);
    $warna = trim($_POST['warna']);
    $pemilik = trim($_POST['pemilik']);
    $tanggal_servis = trim($_POST['tanggal_servis']);

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $target = 'uploads/' . basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    } else {
        $gambar = $row['gambar'];
    }

    $query = "UPDATE kendaraan SET 
                plat_nomor='$plat_nomor',
                jenis_kendaraan='$jenis',
                warna='$warna',
                pemilik='$pemilik',
                tanggal_servis='$tanggal_servis',
                gambar='$gambar'
              WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Gagal memperbarui data: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Kendaraan</title>
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
        img {
            display: block;
            margin: 10px 0;
            border-radius: 5px;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            background: #6c757d;
            padding: 8px 15px;
            border-radius: 5px;
        }
        a:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <h2>Edit Data Kendaraan</h2>
    
    <form method="post" enctype="multipart/form-data">
        <label>Plat Nomor:</label>
        <input type="text" name="plat_nomor" value="<?= htmlspecialchars($row['plat_nomor']); ?>" required>

        <label>Jenis Kendaraan:</label>
        <input type="text" name="jenis_kendaraan" value="<?= htmlspecialchars($row['jenis_kendaraan']); ?>" required>

        <label>Warna:</label>
        <input type="text" name="warna" value="<?= htmlspecialchars($row['warna']); ?>">

        <label>Pemilik:</label>
        <input type="text" name="pemilik" value="<?= htmlspecialchars($row['pemilik']); ?>">

        <label>Tanggal Servis:</label>
        <input type="date" name="tanggal_servis" value="<?= htmlspecialchars($row['tanggal_servis']); ?>">

        <label>Gambar Saat Ini:</label>
        <?php if (!empty($row['gambar'])): ?>
            <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" width="120" alt="Gambar Kendaraan">
        <?php else: ?>
            <p><i>Tidak ada gambar</i></p>
        <?php endif; ?>

        <label>Ganti Gambar (opsional):</label>
        <input type="file" name="gambar" accept="image/*">

        <button type="submit" name="update">Update Data Kendaraan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>