<?php
include('db.php');

if (isset($_POST['insert'])) {

    $name     = $_POST['product'];
    $type     = $_POST['type'];
    $quantity = (int)$_POST['quantity'];
    $status   = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO categories (product, type, quantity, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $type, $quantity, $status);
    $stmt->execute();

    header("Location: categories.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Categories - Mazer Admin Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <?php include('sidebar.php'); ?>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
    </div>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                User category updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        
        <div class="card">
            <div class="card-header">
                <h4>Add Product Category</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="categories.php">

                    <input type="text" name="product" class="form-control mb-2"
                           placeholder="Product" required>

                    <input type="text" name="type" class="form-control mb-2"
                           placeholder="Type" required>

                    <input type="number" name="quantity" class="form-control mb-2"
                           placeholder="Quantity" min="0" required>

                    <select name="status" class="form-control mb-2" required>
                        <option value=""> Select Status </option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <button type="submit" name="insert" class="btn btn-primary">
                        Add Product Category
                    </button>

                </form>
            </div>
        </div>

        
        <div class="card mt-4">
            <div class="card-header">
                <h4>Product Category List</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM categories ORDER BY id ASC");
                        while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['product']) ?></td>
                            <td><?= htmlspecialchars($row['type']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status'] === 'active' ? 'success' : 'secondary' ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $row['id'] ?>"
                                   class="btn btn-warning btn-sm">Edit</a>
                                <a href="categories.php?delete=<?= $row['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this user category?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>