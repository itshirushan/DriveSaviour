<?php
session_start();
require('../navbar/nav.php');
include_once('../../connection.php');

// Check if the session variable is set and not null
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

// Fetch all data from the shops table where the ownerEmail matches the logged-in user's email
$shop_data = [];
if (!empty($loggedInOwnerEmail)) {
    $stmt = $conn->prepare("SELECT * FROM shops WHERE ownerEmail = ?");
    $stmt->bind_param("s", $loggedInOwnerEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $shop_data[] = $row;
        }
    } else {
        echo "No results found for the given owner email.";
    }
    $stmt->close();
} else {
    echo "No logged-in owner email found.";
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manage</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="main_container">
    <?php if ($message == 'insert_success'): ?>
        <p>Shop added successfully.</p>
    <?php elseif ($message == 'update_success'): ?>
        <p>Shop updated successfully.</p>
    <?php elseif ($message == 'delete_success'): ?>
        <p>Shop deleted successfully.</p>
    <?php endif; ?>

    <h1>Manage Your Shops</h1>

    <!-- Table -->
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Shop Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th>Branch</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shop_data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['shop_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['number']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['branch']) ?></td>
                        <td>
                            <a href="edit_shop.php?id=<?= htmlspecialchars($row['id']) ?>">Edit</a>
                            <a href="delete_shop.php?id=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Are you sure you want to delete this shop?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
