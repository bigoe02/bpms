<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the AJAX request
    $employeeName = $_POST['employeeName'];
    $leaveType = $_POST['leaveType'];
    $leaveStatus = $_POST['leaveStatus'];

    // Map the status to the corresponding numeric value
    $statusValue = 0;
    if ($leaveStatus === 'pending') {
        $statusValue = 1;
    } elseif ($leaveStatus === 'approved') {
        $statusValue = 2;
    } elseif ($leaveStatus === 'rejected') {
        $statusValue = 3;
    }

    // Update the leave status in the database
    $query = "UPDATE tblleave SET leave_status = ? WHERE employeeName = ? AND leave_type = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("iss", $statusValue, $employeeName, $leaveType);

    if ($stmt->execute()) {
        echo "Leave status updated successfully.";
    } else {
        echo "Error updating leave status: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>