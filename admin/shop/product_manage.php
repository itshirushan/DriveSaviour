<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['id'];
    $shop_id = $_POST['shop_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $quantity_available = (int) $_POST['quantity_available'];
    $price = (float) $_POST['price'];
    $cat_id = (int)$_POST['manage_category_name'];

    if ($_POST['action'] == 'edit') {
        // Update query
        $sql = "UPDATE products SET product_name='$product_name', cat_id='$cat_id', quantity_available='$quantity_available', price='$price' WHERE id='$product_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: products.php?shop_id=" . $shop_id . "&message=edit");
            exit;
        } else {
            $error = mysqli_error($conn);
            header("Location: products.php?shop_id=" . $shop_id . "&message=error&error=" . urlencode($error));
            exit;
        }
    } elseif ($_POST['action'] == 'delete') {
        // Delete query
        $sql = "DELETE FROM products WHERE id='$product_id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: products.php?shop_id=" . $shop_id . "&message=delete");
            exit;
        } else {
            $error = mysqli_error($conn);
            header("Location: products.php?shop_id=" . $shop_id . "&message=error&error=" . urlencode($error));
            exit;
        }
    }
} else {
    header("Location: products.php");
    exit;
}
