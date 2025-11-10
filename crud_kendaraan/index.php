<?php
include 'db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$countSql = "SELECT COUNT(*) AS total FROM kendaraan 
             WHERE plat_nomor LIKE '%$search%' 
             OR pemilik LIKE '%$search%'";
$countResult = mysqli_query($conn, $countSql);
$countRow = mysqli_fetch_assoc($countResult);
$total = $countRow['total'];
$pages = ceil($total / $limit);

$sql = "SELECT * FROM kendaraan 
        WHERE plat_nomor LIKE '%$search%' 
        OR pemilik LIKE '%$search%' 
        LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Kendaraan</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f7f9fb;
      margin: 0;
      padding: 30px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    form {
      margin-bottom: 20px;
    }

    input[type="text"] {
      padding: 8px 12px;
      width: 250px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      padding: 8px 15px;
      border: none;
      background-color: #007bff;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    a {
      text-decoration: none;
      color: #007bff;
    }

    a:hover {
      text-decoration: underline;
    }

    .add-btn {
      display: inline-block;
      margin-bottom: 20px;
      font-weight: bold;
    }

    table {
      width: 80%;
      margin: 0 auto;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }

    th {
      background-color: #007bff;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    img {
      border-radius: 5px;
    }

    .pagination {
      margin-top: 20px;
    }

    .pagination a {
      color: #007bff;
      padding: 8px 12px;
      border: 1px solid #ddd;
      margin: 0 3px;
      border-radius: 5px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .pagination a:hover {
      background-color: #007bff;
      color: white;
    }

    .active-page {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }

    .kotak {
      padding: 4px 8px;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
    }
    .kotak-edit {
      background-color: #e96310ff; 
    }
    .kotak-hapus {
      background-color: #d40f0fff; 
    }
  </style>
</head>
<body>

  <h2>Data Kendaraan</h2>

  <form method="GET">
    <input type="text" name="search" placeholder="Cari plat nomor / pemilik..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
  </form>

  <a class="add-btn" href="add.php">+ Tambah Kendaraan</a>

  <table>
    <tr>
      <th>No</th>
      <th>Plat Nomor</th>
      <th>Jenis Kendaraan</th>
      <th>Warna</th>
      <th>Pemilik</th>
      <th>Tanggal Servis</th>
      <th>Gambar</th>
      <th>Aksi</th>
    </tr>

    <?php 
    $no = $start + 1;
    while ($row = mysqli_fetch_assoc($result)) { 
      echo "<tr>
              <td>$no</td>
              <td>{$row['plat_nomor']}</td>
              <td>{$row['jenis_kendaraan']}</td>
              <td>{$row['warna']}</td>
              <td>{$row['pemilik']}</td>
              <td>{$row['tanggal_servis']}</td>
              <td><img src='uploads/{$row['gambar']}' width='80'></td>
              <td>
                <a href='edit.php?id={$row['id']}' class='kotak kotak-edit'>Edit</a> |
                <a href='delete.php?id={$row['id']}' class='kotak kotak-hapus' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Delete</a>
              </td>
            </tr>";
      $no++;
    } 
    ?>
  </table>

  <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
           class="<?php echo ($i == $page) ? 'active-page' : ''; ?>">
           <?php echo $i; ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>

</body>
</html>