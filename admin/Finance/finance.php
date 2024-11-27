<?php
require '../navbar/navbar.php';
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

try {
    $stmt = $conn->prepare("
        SELECT o.*, p.product_name, p.shop_id, s.shop_name 
        FROM (
            SELECT * FROM orders WHERE payment_status = 'Pending'
            UNION ALL
            SELECT * FROM mech_orders WHERE payment_status = 'Pending'
        ) o
        JOIN products p ON o.product_id = p.id 
        JOIN shops s ON p.shop_id = s.id
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch order data and calculate total seller income
    $orders_data = [];
    $total_seller_income = 0;
    while ($row = $result->fetch_assoc()) {
        $orders_data[] = $row;
        $total_seller_income += $row['seller_income'];
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
    <title>Income</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <div class="main_container">
        <h1 class="title">Payment Data</h1>
        <div class="total-income">
            <h3>Total Seller Income: LKR <?php echo number_format($total_seller_income, 2); ?></h3>
        </div>

        <form action="stripe_payment.php" method="POST">
            <input type="hidden" name="total_amount" value="<?php echo $total_seller_income; ?>">
            <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
            <button type="submit" class="btn">Pay Commission</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Product Name</th>
                    <th>Purchase Date</th>
                    <th>Quantity</th>
                    <th>Seller Income</th>
                    <th>Seller Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders_data)): ?>
                    <?php foreach ($orders_data as $order): ?>
                        <tr>
                            <td data-cell="Reference Number"><?php echo $order['reference_number']; ?></td>
                            <td data-cell="Product Name"><?php echo $order['product_name']; ?></td>
                            <td data-cell="Purchase Date"><?php echo $order['purchase_date']; ?></td>
                            <td data-cell="Quantity"><?php echo $order['quantity']; ?></td>
                            <td data-cell="Seller Income"><?php echo $order['seller_income']; ?></td>
                            <td data-cell="Status"><?php echo $order['payment_status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="alert alert-danger">No Pending orders found for your shop.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
<br>
    </div>
</body>
</html>
