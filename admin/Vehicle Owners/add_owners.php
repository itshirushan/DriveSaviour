<?php
// Start the session
session_start();


require('../navbar/navbar.php');
include_once('../../connection.php');

// Fetch all data from the vehicle_owner table where the email matches the logged-in user's email
$owner_data = [];
if (!empty($loggedInOwnerEmail)) {
    $stmt = $conn->prepare("SELECT * FROM vehicle_owner WHERE email = ?");
    $stmt->bind_param("s", $loggedInOwnerEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $owner_data[] = $row;
        }
    } else {
        echo "No results found for the given email.";
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
    <title>Manage Vehicle Owners</title>
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
        <div class="alert alert-danger">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <br>
  
    <div class="title">
        <h1>Manage Vehicle Owners</h1>
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

            <div class="form-row">
                <div class="form-group">
                    <label for="ownerEmail">Owner Email:</label>
                    <input type="text" id="ownerEmail" name="ownerEmail" value="<?= htmlspecialchars($loggedInOwnerEmail) ?>" required readonly>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" name="action" value="insert" class="batch view-link">Add Owner</button>
    </form>


    <div class="searchbars">
        <!-- Search bar -->
        <div class="search-bar">
            <label for="search">Search by Name:</label>
            <input type="text" id="search" class="search-select" placeholder="Name">
            <button id="search-icon"><i class="fas fa-search"></i></button>
        </div>
        <br>
    </div>

    <!-- Table -->
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Owner </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="owner-tbody">
                <?php foreach ($owner_data as $row): ?>
                    <tr>
                        <td data-cell="Name"><?= htmlspecialchars($row['name']) ?></td>
                        <td data-cell="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-cell="Phone"><?= htmlspecialchars($row['phone']) ?></td>
                        <td data-cell="Address"><?= htmlspecialchars($row['address']) ?></td>
                        <td data-cell="City"><?= htmlspecialchars($row['city']) ?></td>
                        <td data-cell="Owner Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <button class="manage-button view-link" 
                                    data-id="<?= htmlspecialchars($row['id']) ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                    data-address="<?= htmlspecialchars($row['address']) ?>"
                                    data-city="<?= htmlspecialchars($row['city']) ?>">
                                Manage
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- manage modal -->
    <div id="manageOwnerModal" class="modal">
        <div class="modal-content">
            <span id="closeManageOwnerModal" class="close">&times;</span>
            <h2>Manage Owner</h2>
            <form id="manageOwnerForm" action="owner_manage.php" method="POST">
                <input type="hidden" id="manage_owner_id" name="id">
                <div class="form-group">
                    <label for="manage_name">Name:</label>
                    <input type="text" id="manage_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="manage_email">Email:</label>
                    <input type="email" id="manage_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="manage_phone">Phone:</label>
                    <input type="text" id="manage_phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="manage_address">Address:</label>
                    <input type="text" id="manage_address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="manage_city">City:</label>
                    <input type="text" id="manage_city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="manage_password">Password (Leave blank to keep current):</label>
                    <input type="password" id="manage_password" name="password">
                </div>
                
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    var manageOwnerModal = document.getElementById("manageOwnerModal");
    var closeManageOwnerModal = document.getElementById("closeManageOwnerModal");

    // Close modal when close button is clicked
    closeManageOwnerModal.onclick = function() {
        manageOwnerModal.style.display = "none";
    }

    // Close modal when user clicks outside of it
    window.onclick = function(event) {
        if (event.target == manageOwnerModal) {
            manageOwnerModal.style.display = "none";
        }
    }

    // Manage Owner functionality
    document.querySelectorAll('.manage-button').forEach(button => {
        button.addEventListener('click', function() {
            var ownerId = this.dataset.id;
            var name = this.dataset.name;
            var email = this.dataset.email;
            var phone = this.dataset.phone;
            var address = this.dataset.address;
            var city = this.dataset.city;

            document.getElementById('manage_owner_id').value = ownerId;
            document.getElementById('manage_name').value = name;
            document.getElementById('manage_email').value = email;
            document.getElementById('manage_phone').value = phone;
            document.getElementById('manage_address').value = address;
            document.getElementById('manage_city').value = city;

            manageOwnerModal.style.display = "block";
        });
    });

    // Confirm deletion for Owner
    document.getElementById("manageOwnerForm").addEventListener("submit", function(event) {
        var action = document.activeElement.value;
        if (action === 'delete') {
            var confirmed = confirm("Are you sure you want to delete this owner?");
            if (!confirmed) {
                event.preventDefault();
            }
        }
    });
</script>

</body>
</html>
