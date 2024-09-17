<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $shop_name = trim(mysqli_real_escape_string($conn, $_POST['shop_name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $number = preg_match("/^[0-9]{10}$/", $_POST['number']) ? $_POST['number'] : null;
    $address = trim(mysqli_real_escape_string($conn, $_POST['address']));
    $branch = trim(mysqli_real_escape_string($conn, $_POST['branch']));
    $ownerEmail = filter_var($_POST['ownerEmail'], FILTER_VALIDATE_EMAIL);

    // Check for valid input data
    if ($email && $number && $ownerEmail) {
        // Prepare statement to check if ownerEmail exists in shop_owner table
        $checkEmailQuery = "SELECT email FROM shop_owner WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param("s", $ownerEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the ownerEmail exists
        if ($result->num_rows > 0) {
            // Prepare statement for inserting new shop
            $insertQuery = "INSERT INTO shops (shop_name, email, number, address, branch, ownerEmail) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssss", $shop_name, $email, $number, $address, $branch, $ownerEmail);

            // Execute the insert query and check for success
            if ($stmt->execute()) {
                // Redirect to shop.php with a success message
                header("Location: add_owners.php?message=insert");
                exit;
            } else {
                // Redirect with an error message in case of failure
                $error = $stmt->error;
                header("Location: add_owners.php?message=error&error=" . urlencode($error));
                exit;
            }
        } else {
            // Redirect with error if ownerEmail doesn't exist
            $error = "Owner email does not exist in the shop_owner table.";
            header("Location: add_owners.php?message=error&error=" . urlencode($error));
            exit;
        }
    } else {
        // Handle invalid input data
        $error = "Invalid input data. Please check the email, number, and owner email.";
        header("Location: add_owners.php?message=error&error=" . urlencode($error));
        exit;
    }
} else {
    // Redirect if the request method is not POST
    header("Location: add_owners.php");
    exit;
}
