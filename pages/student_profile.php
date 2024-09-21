<?php
session_start();
include 'db.php'; // Assuming you have a connection file

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch student data from the database
$student_id = $_SESSION['student_id'];
$sql = "SELECT s.name, s.email, s.department_id, s.student_id, s.profile_photo, t.name AS advisor_name 
        FROM students s 
        LEFT JOIN advising a ON s.student_id = a.student_id 
        LEFT JOIN teachers t ON a.teacher_id = t.teacher_id 
        WHERE s.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Update profile logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department_id = $_POST['department'];
    
    // Profile picture upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $photo_path = "uploads/" . basename($_FILES['profile_photo']['name']);
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $photo_path);
        $student['profile_photo'] = $photo_path;
    }

    $update_sql = "UPDATE students SET name = ?, email = ?, department_id = ?, profile_photo = ? WHERE student_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssiss", $name, $email, $department_id, $student['profile_photo'], $student_id);
    
    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-7xl mx-auto py-12 px-4">
        <h1 class="text-3xl font-bold">Student Profile</h1>

        <form action="" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
            <div>
                <label class="block font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="<?= $student['name'] ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="<?= $student['email'] ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Department</label>
                <select name="department" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    <option value="1" <?= $student['department_id'] == 1 ? 'selected' : '' ?>>Computer Science</option>
                    <option value="2" <?= $student['department_id'] == 2 ? 'selected' : '' ?>>Mechanical Engineering</option>
                    <!-- Add other departments here -->
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Student ID</label>
                <input type="text" name="student_id" value="<?= $student['student_id'] ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2" readonly>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Profile Photo</label>
                <input type="file" name="profile_photo" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <div>
                <label class="block font-medium text-gray-700">Advisor</label>
                <input type="text" name="advisor" value="<?= $student['advisor_name'] ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2" readonly>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Update Profile</button>
        </form>

        <div class="mt-8">
            <a href="change_password.php" class="text-blue-600">Change Password</a>
        </div>
    </div>
</body>
</html>
