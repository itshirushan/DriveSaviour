<?php
    require '../navbar/nav.php';
    require '../../connection.php';
    
    if (isset($_SESSION['email'])) {
        $loggedInEmail = $_SESSION['email'];
    } else {
        // If user is not logged in, redirect them to the login page
        header("Location: login.php");
        exit();
    }

    // Fetch shop data from the shops table where ownerEmail matches logged-in user's email
    $sql = "SELECT * FROM shops WHERE ownerEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $loggedInEmail);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop List</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Content Section -->
    <div class="shop-container">
        <h1>Shops</h1>
        <div class="shop-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Dynamic shop data
                    echo "
                    <div class='shop-card'>
                        <h2>{$row['shop_name']}</h2>
                        <p>Branch: {$row['branch']}</p>
                        <p>Address: {$row['address']}</p>
                        <p>Phone: {$row['number']}</p>
                        <!-- Hardcoded Ratings and Reviews -->
                        <p><strong>Ratings:</strong> 4.5 ★★★★☆ (120 reviews)</p>
                        <button class='view-products-btn' onclick='viewProducts({$row['id']})'>View Products</button>
                    </div>
                    ";
                }
            } else {
                echo "<p>No shops available.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        function viewProducts(shopId) {
            // Redirect to products.php with shop ID as query parameter
            window.location.href = "products.php?shop_id=" + shopId;
        }
    </script>
</body>
</html>
