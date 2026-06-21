<?php
include('db.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "Invalid ID";
    exit;
}

$sql  = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res  = $stmt->get_result();
$row  = $res->fetch_assoc();

if (!$row) {
    header("Location: categories.php");
    exit;
}

$errors = [];

if (isset($_POST['update'])) {

    $name     = trim($_POST['name']);
    $position = trim($_POST['position']);
    $age      = (int)$_POST['age'];
    $status   = $_POST['status'];

    if ($name === '')          $errors[] = "Name is required";
    if ($position === '')      $errors[] = "Position is required";
    if ($age <= 0)             $errors[] = "Age must be a positive number";
    if ($status === '')        $errors[] = "Status is required";

    if (empty($errors)) {
        $sql  = "UPDATE categories SET product=?, type=?, quantity=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $name, $position, $age, $status, $id);

        if ($stmt->execute()) {
            header("Location: categories.php?success=updated");
            exit;
        } else {
            $errors[] = "Update failed, please try again";
        }
    } else {
        $row['product']     = $name;
        $row['type'] = $position;
        $row['quantity']      = $age;
        $row['status']   = $status;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Category - Mazer Admin Dashboard</title>

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

        <div class="card">
            <div class="card-header">
                <h4>Edit product Category</h4>
            </div>
            <div class="card-body">

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">

                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                    <div class="mb-2">
                        <label class="form-label">Product</label>
                        <input type="text" name="name"
                               value="<?= htmlspecialchars($row['product']) ?>"
                               class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Type</label>
                        <input type="text" name="position"
                               value="<?= htmlspecialchars($row['type']) ?>"
                               class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="age"
                               value="<?= $row['quantity'] ?>"
                               class="form-control" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active"   <?= $row['status'] === 'active'   ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $row['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" name="update" class="btn btn-primary">
                        Update Category
                    </button>

                    <a href="categories.php" class="btn btn-light-secondary">Cancel</a>

                </form>

            </div>
        </div>

    </div>
</body>
</html>