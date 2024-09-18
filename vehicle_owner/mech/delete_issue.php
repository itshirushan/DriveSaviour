<?php
// Start the session
session_start();

// Include your database connection
require '../../connection.php';

// Set the response header to return JSON
header('Content-Type: application/json');

// Get the POSTed data from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);

// Check if the issue ID is set
if (isset($data['issueID'])) {
    // Sanitize the input to prevent SQL injection
    $issueID = intval($data['issueID']);

    // Prepare the delete query
    $query = "DELETE FROM vehicleissues WHERE id = ?";

    if ($stmt = $conn->prepare($query)) {
        // Bind the issue ID parameter
        $stmt->bind_param("i", $issueID);

        // Execute the query
        if ($stmt->execute()) {
            // If successful, return success message
            echo json_encode(['success' => true, 'message' => 'Issue deleted successfully']);
        } else {
            // If execution failed, return error message
            echo json_encode(['success' => false, 'message' => 'Error deleting issue']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // If preparation of the query failed
        echo json_encode(['success' => false, 'message' => 'Error preparing statement']);
    }
} else {
    // If issueID was not sent in the request
    echo json_encode(['success' => false, 'message' => 'No issue ID provided']);
}

// Close the database connection
$conn->close();
?>
