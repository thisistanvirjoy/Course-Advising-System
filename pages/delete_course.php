<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$course_id = $_GET['id'];

$sql = "DELETE FROM courses WHERE id = $course_id";
if ($conn->query($sql) === TRUE) {
    header("Location: manage_courses.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
