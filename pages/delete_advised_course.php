<?php
include 'db.php';  // Include database connection
session_start(); // Start session to access the logged-in student's ID

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit;
}

$user_id = $_SESSION['user_id']; // Get logged-in user's user_id from session

// Fetch the corresponding student_id using user_id
$sql = "SELECT student_id FROM students WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_id = $row['student_id'];  // Now you have the student_id
} else {
    echo "Error: Student ID not found.";
    exit;
}

// Handle course deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Delete course from registration table for the logged-in student
    $sql_delete = "DELETE FROM registration WHERE course_id = '$course_id' AND student_id = '$student_id'";
    
    if ($conn->query($sql_delete) === true) {
        // Redirect back to student dashboard after successful deletion
        header("Location: student_dashboard.php?message=deleted");
        exit;
    } else {
        echo "Error deleting course: " . $conn->error;
    }
}
?>

