<?php
// Start the session
session_start();
require('../navbar/navbar.php');
include_once('../../connection.php');

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle Owners</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="add_owners.css">
</head>

<body>
<div class="main_container">
    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success">The vehicle owner was created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">The vehicle owner was deleted successfully.</div>
    <?php elseif ($message == 'edit'): ?>
        <div class="alert alert-success">The vehicle owner was updated successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <br>
  
    <div class="title">
        <h1>Add Vehicle Owners</h1>
        <br><br>     
    </div>

    <form action="addowner.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" name="action" value="insert" class="batch view-link">Add Owner</button>
    </form>


</div>


</body>
</html>
