<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "marketplace");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Filter dan pencarian
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM products WHERE 1";
if (!empty($search)) {
    $sql = "SELECT * FROM products WHERE nama LIKE '" . $conn->real_escape_string($search) . "%'";
}

echo "Generated Query: " . $sql; // Debugging query
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Marketplace</title>
</head>

<body>
    <h1>Daftar Produk</h1>
    <form method="get" action="">
        <input type="text" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
        <select name="category">
            <option value="">Semua Kategori</option>
            <option value="Elektronik" <?= $category === 'Elektronik' ? 'selected' : '' ?>>Elektronik</option>
            <option value="Pakaian" <?= $category === 'Pakaian' ? 'selected' : '' ?>>Pakaian</option>
            <!-- Tambahkan kategori lainnya -->
        </select>
        <button type="submit">Cari</button>
    </form>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?= htmlspecialchars($row['nama']) ?></strong> -
                Rp<?= number_format($row['price'], 2) ?><br>
                <?= htmlspecialchars($row['description']) ?>
            </li>
        <?php endwhile; ?>
    </ul>
</body>

</html>