<?php
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $issueID = $_POST['issueID'];
    $vehicleIssue = $_POST['vehicleIssue'];

    // Validate the input
    if (!empty($issueID) && !empty($vehicleIssue)) {
        // Update query
        $query = "UPDATE vehicleissues SET vehicle_issue = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param('si', $vehicleIssue, $issueID);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header('Location: breakdown_Details.php?success=Issue updated');
            } else {
                header('Location: breakdown_Details.php?error=Failed to update issue');
            }
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    } else {
        header('Location: breakdown_list.php?error=Invalid input');
    }
}
