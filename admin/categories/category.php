<?php
    require '../navbar/navbar.php';
    require '../../connection.php';

    $category_data = [];
    $stmt = $conn->prepare("SELECT * FROM category");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $category_data[] = $row;
    }
    $stmt->close();

    $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../../shop_owner/shop/style.css">
    
    <style>
        body{
            margin-left: 300px;
            margin-top: 100px;
        }
    </style>

</head>
<body>
<?php if ($message == 'insert'): ?>
        <div class="alert alert-success" id="alertMessage">The Category was created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger" id="alertMessage">The Category was deleted successfully.</div>
    <?php elseif ($message == 'edit'): ?>
        <div class="alert alert-success" id="alertMessage">The Category was updated successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger" id="alertMessage">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <div class="title">
        <h1>Category Management</h1>
        <br><br>     
    </div>

    <form action="addcategory.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="cat_name">Category Name:</label>
                    <input type="text" id="cat_name" name="cat_name" required>
                </div>

                <div class="form-group">
                    <label for="cdate">Created Date:</label>
                    <input type="date" id="cdate" name="cdate" required>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50"></textarea>
                    
                </div>

                
            </div>

        </div>
        <br>
        <button type="submit" name="action" value="insert" class="batch view-link">Add Category</button>
    </form>

    <!-- Table to display category details -->
    <div class="category-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($category_data as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['id']); ?></td>
                        <td><?= htmlspecialchars($category['category_name']); ?></td>
                        <td><?= htmlspecialchars($category['description']); ?></td>
                        <td><?= htmlspecialchars($category['created_date']); ?></td>
                        <td>
                            <button class="manage-button view-link" 
                                    data-id="<?= htmlspecialchars($category['id']) ?>"
                                    data-category_name="<?= htmlspecialchars($category['category_name']) ?>"
                                    data-description="<?= htmlspecialchars($category['description']) ?>"
                                    data-created_date="<?= htmlspecialchars($category['created_date']) ?>">
                                Manage
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Manage Category Modal -->
    <div id="manageCategoryModal" class="modal">
        <div class="modal-content">
            <span id="closeManageCategoryModal" class="close">&times;</span>
            <h2>Manage Category</h2>
            <form id="manageCategoryForm" action="category_manage.php" method="POST">
                <input type="hidden" id="manage_category_id" name="id">
                
                <div class="form-group">
                    <label for="manage_category_name">Category Name:</label>
                    <input type="text" id="manage_category_name" name="category_name" required>
                </div>
                <div class="form-group">
                    <label for="manage_description">Description:</label>
                    <textarea id="manage_description" name="description" rows="4" cols="50" required></textarea>
                </div>
                <div class="form-group">
                    <label for="manage_created_date">Created Date:</label>
                    <input type="date" id="manage_created_date" name="created_date" required>
                </div>
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>
</body>

<script>
    // Close modal
    document.getElementById("closeManageCategoryModal").onclick = function() {
            document.getElementById("manageCategoryModal").style.display = "none";
        };

    // Manage button click event
    document.querySelectorAll('.manage-button').forEach(button => {
        button.addEventListener('click', function() {
            // Retrieve data attributes directly from the button
            var categoryId = this.getAttribute('data-id');
            var categoryName = this.getAttribute('data-category_name');
            var description = this.getAttribute('data-description');
            var createdDate = this.getAttribute('data-created_date');

            // Fill modal inputs with the retrieved data
            document.getElementById('manage_category_id').value = categoryId;
            document.getElementById('manage_category_name').value = categoryName;
            document.getElementById('manage_description').value = description;
            document.getElementById('manage_created_date').value = createdDate;

            // Display the modal
            document.getElementById('manageCategoryModal').style.display = 'block';
        });
    });


    // Close modal on clicking outside content
    window.onclick = function(event) {
        var modal = document.getElementById("manageCategoryModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };

    // Confirm deletion for Product
    document.getElementById("manageCategoryModal").addEventListener("submit", function(event) {
        var action = document.activeElement.value;
        if (action === 'delete') {
            var confirmed = confirm("Are you sure you want to delete this Category?");
            if (!confirmed) {
                event.preventDefault();
            }
        }
    });

    setTimeout(function() {
        var alertElement = document.getElementById('alertMessage');
        if (alertElement) {
            alertElement.style.display = 'none';
        }
    }, 10000);
</script>
</html>