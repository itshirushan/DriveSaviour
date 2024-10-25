<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

require('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $shop_id = filter_var($_POST['shop_id'], FILTER_SANITIZE_NUMBER_INT);
    $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
    $quantity_available = (int) $_POST['quantity_available'];
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT); // Capture the category ID

    // Image upload handling
    if (isset($_FILES['image'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../../uploads/' . $image;

        // Check if product already exists in the same shop
        $select_products = mysqli_prepare($conn, "SELECT * FROM products WHERE product_name = ? AND shop_id = ?");
        mysqli_stmt_bind_param($select_products, "si", $product_name, $shop_id);
        mysqli_stmt_execute($select_products);
        mysqli_stmt_store_result($select_products);

        if (mysqli_stmt_num_rows($select_products) > 0) {
            $error = "Product name already exists!";
            header("Location: products.php?shop_id=$shop_id&message=error&error=" . urlencode($error));
            exit;
        } else {
            // Check if image size is too large
            if ($image_size > 2000000) {
                $error = "Image size is too large!";
                header("Location: products.php?message=error&error=" . urlencode($error));
                exit;
            } else {
                // Debugging: Check if the uploads directory is writable
                if (!is_writable('../../uploads/')) {
                    $error = "Upload directory is not writable.";
                    header("Location: products.php?message=error&error=" . urlencode($error));
                    exit;
                } else {
                    // Move the uploaded image to the uploads directory
                    move_uploaded_file($image_tmp_name, $image_folder);

                    // Insert the new product into the database
                    $insert_product = mysqli_prepare($conn, "INSERT INTO products (product_name, cat_id, shop_id, quantity_available, price, image_url) VALUES (?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($insert_product, "siidss", $product_name, $category_id, $shop_id, $quantity_available, $price, $image_folder); // Bind parameters

                    if (mysqli_stmt_execute($insert_product)) {
                        header("Location: products.php?shop_id=$shop_id&message=insert");
                    } else {
                        $error = "Failed to add the product.";
                        header("Location: products.php?shop_id=$shop_id&message=error&error=" . urlencode($error));
                    }
                    mysqli_stmt_close($insert_product);
                }
            }
        }
        mysqli_stmt_close($select_products);
    }
}
