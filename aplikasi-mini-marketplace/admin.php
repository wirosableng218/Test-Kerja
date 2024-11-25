<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "marketplace");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $category = $_POST['category'];

        $stmt = $conn->prepare("INSERT INTO products (name, price, description, category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $description, $category);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
</head>

<body>
    <h1>Admin Panel</h1>
    <a href="logout.php">Logout</a>
    <h2>Tambah Produk</h2>
    <form method="post" action="">
        <input type="text" name="name" placeholder="Nama Produk" required>
        <input type="number" name="price" placeholder="Harga" step="0.01" required>
        <textarea name="description" placeholder="Deskripsi" required></textarea>
        <input type="text" name="category" placeholder="Kategori" required>
        <button type="submit" name="add">Tambah</button>
    </form>
    <h2>Daftar Produk</h2>
    <ul>
        <?php while ($row = $products->fetch_assoc()): ?>
            <li>
                <strong><?= htmlspecialchars($row['name']) ?></strong> - Rp<?= number_format($row['price'], 2) ?><br>
                <?= htmlspecialchars($row['description']) ?> <br>
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete">Hapus</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</body>

</html>