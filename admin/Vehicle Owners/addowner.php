<?php
session_start();
include_once('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);  // Trim whitespace and get the raw password
    $phone = preg_match("/^[0-9]{10}$/", $_POST['phone']) ? $_POST['phone'] : null;
    $address = trim(mysqli_real_escape_string($conn, $_POST['address']));
    $city = trim(mysqli_real_escape_string($conn, $_POST['city']));

    // Check if the email and phone are valid
    if ($email && $phone && $password) {
        // Encrypt the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare statement to check if email exists in vehicle_owner table
        $checkEmailQuery = "SELECT email FROM vehicle_owner WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the email already exists
        if ($result->num_rows == 0) {
            // Prepare statement for inserting new vehicle owner
            $insertQuery = "INSERT INTO vehicle_owner (name, email, password, phone, address, city) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $phone, $address, $city);

            // Execute the insert query and check for success
            if ($stmt->execute()) {
                // Redirect to add_owners.php with a success message
                header("Location: add_owners.php?message=insert");
                exit;
            } else {
                // Redirect with an error message in case of failure
                $error = $stmt->error;
                header("Location: add_owners.php?message=error&error=" . urlencode($error));
                exit;
            }
        } else {
            // Redirect with error if email already exists
            $error = "The email is already registered.";
            header("Location: add_owners.php?message=error&error=" . urlencode($error));
            exit;
        }
    } else {
        // Handle invalid input data
        $error = "Invalid input data. Please check the email, phone, and password.";
        header("Location: add_owners.php?message=error&error=" . urlencode($error));
        exit;
    }
} else {
    // Redirect if the request method is not POST
    header("Location: add_owners.php");
    exit;
}
