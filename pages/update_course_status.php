<?php
require 'db.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id']) && isset($_POST['action'])) {
    $course_id = $_POST['course_id'];
    $action = $_POST['action'];

    // Set status based on action (accept/reject)
    $status = ($action === 'accept') ? 'Accepted' : 'Rejected';

    // Update the course status for the student
    $sql = "UPDATE registration SET status = '$status' WHERE course_id = '$course_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Course successfully updated to: $status";
    } else {
        echo "Error updating course: " . $conn->error;
    }
}
?>
