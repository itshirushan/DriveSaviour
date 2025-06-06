<?php
ob_start(); 

require '../navbar/navbar.php';
require '../../connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../adminLogin/adminLogin.php");
    exit();
} 

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}


$loggedInOwnerEmail = $_SESSION['email'];

try {
    // count of mechanics
    $stmt = $conn->prepare("SELECT COUNT(*) as mechanic_count FROM mechanic");
    $stmt->execute();
    $result = $stmt->get_result();
    $mechanic_count = $result->fetch_assoc()['mechanic_count'];

    // count of users
    $stmt = $conn->prepare("SELECT COUNT(*) as user_count FROM vehicle_owner");
    $stmt->execute();
    $result = $stmt->get_result();
    $user_count = $result->fetch_assoc()['user_count'];

    // count of products
    $stmt = $conn->prepare("SELECT COUNT(*) as shop_count FROM shops");
    $stmt->execute();
    $result = $stmt->get_result();
    $products_count = $result->fetch_assoc()['shop_count'];

    // products that need restocking
    $stmt = $conn->prepare("SELECT p.*, s.* 
                            FROM products p 
                            JOIN shops s ON p.shop_id = s.id 
                            WHERE p.quantity_available <= 5 
                            AND s.ownerEmail = ?");
    $stmt->bind_param("s", $loggedInOwnerEmail);

    $stmt->execute();
    $result = $stmt->get_result();

    $product_data = [];

    while ($row = $result->fetch_assoc()) {
        $product_data[] = $row;
    }
    $stmt->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" type="image/png" href="/img/Latte-Cappuccino-Transparent-PNG.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php require '../navbar/navbar.php'; ?>

    <div class="dashboard-container">
        <div class="profile">
            <div class="image">
                <h2>Welcome to Admin Dashboard</h2>
            </div>
        </div>

        <div class="wrapper">
            <div class="container1">
                <img src="../../img/mechanic.png">
                <br><span class="num plus-sign" data-val="<?php echo $mechanic_count; ?>">0</span>
                <br><span class="text">Total Mechanics</span>
            </div>

            <div class="container1">
                <img src="../../img/team.png">
                <br><span class="num plus-sign" data-val="<?php echo $user_count; ?>">0</span>
                <br><span class="text">Total Users</span>
            </div>

            <div class="container1">
                <img src="../../img/checklist.png">
                <br><span class="num" data-val="<?php echo $products_count; ?>">0</span>
                <br><span class="text">Total Shop Items</span>
            </div>
        </div>
        
        
        <div class="chart-section">
        <h2>Commission by Day</h2>
            <?php require '../chart/commissionlinechart.php'; ?>
            </div>

            
        
      
        <div class="content">
            <div class="text">
                <h3>Goods to be Restocked <i class='bx bxs-bell-ring'></i> </h3>
            </div>
        

            <div class="wrappers">
                <?php if(!empty($product_data)): ?>
                    <?php foreach($product_data as $product): ?>
                        <div class="product-card">
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['product_name']; ?>">
                            <div class="product-name"><?php echo $product['product_name']; ?></div>
                            <div class="product-quantity">Available: <?php echo $product['quantity_available']; ?></div>
                            <div class="shop-name">Shop: <?php echo $product['shop_name']; ?> <?php echo $product['branch']; ?> Branch</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products need restocking at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <script>
        let valueDisplays = document.querySelectorAll(".num");
        let interval = 1000;

        valueDisplays.forEach((valueDisplay) => {
            let startValue = 0;
            let endValue = parseInt(valueDisplay.getAttribute("data-val"));
            let duration = Math.floor(interval / endValue);
            let counter = setInterval(function () {
                startValue += 1;
                valueDisplay.textContent = startValue;
                if (startValue === endValue) {
                    clearInterval(counter);
                    if (valueDisplay.classList.contains('plus-sign')) {
                        valueDisplay.textContent += '';
                    }
                }
            }, duration);
        });
    </script>
</body>
</html>
