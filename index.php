<?php
require_once "db_connection.php";

// Tambahkan data ke database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    try {
        $connection = getConnection();
        $sql = "INSERT INTO pelanggan (nama, email) VALUES (:name, :email)";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':email', $email);
        $statement->execute();
        $successMessage = "Data berhasil ditambahkan!";
    } catch (PDOException $e) {
        $errorMessage = "Gagal menambahkan data: " . $e->getMessage();
    }
}

// Ambil data pelanggan
$customers = [];
try {
    $connection = getConnection();
    $sql = "SELECT * FROM pelanggan";
    $statement = $connection->query($sql);
    $customers = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = "Gagal mengambil data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP + HTML</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Data Pelanggan</h1>

        <!-- Pesan sukses/gagal -->
        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php elseif (!empty($errorMessage)) : ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <!-- Form Tambah Data -->
        <div class="card mb-4">
            <div class="card-header">Tambah Pelanggan</div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>

        <!-- Tabel Data Pelanggan -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)) : ?>
                    <?php foreach ($customers as $customer) : ?>
                        <tr>
                            <td><?= $customer['id'] ?></td>
                            <td><?= $customer['nama'] ?></td>
                            <td><?= $customer['email'] ?></td>
                            <td>
                                <a href="edit.php?id=<?= $customer['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=<?= $customer['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
