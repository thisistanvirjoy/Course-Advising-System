<?php
session_start();
require 'db.php';

// Fetch existing user data
$user_id = $_SESSION['user_id'];

// Fetch user information and department name for the teacher
$sql = "SELECT u.name, u.email, t.teacher_id, t.department AS department_code, d.department_name 
        FROM users u
        LEFT JOIN teachers t ON u.id = t.user_id
        LEFT JOIN departments d ON t.department = d.department_code
        WHERE u.id = '$user_id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $department_code = $user['department_code'];
    $department_name = $user['department_name'];
} else {
    echo "Error fetching teacher profile.";
    exit;  // Ensure script halts on error
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);

    // Update the users table (name and email)
    $update_user_sql = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";

    // Update the teachers table (teacher_id and department)
    $update_teacher_sql = "UPDATE teachers SET teacher_id='$teacher_id', department='$department', profile_completed=1 WHERE user_id='$user_id'";

    if ($conn->query($update_user_sql) === TRUE && $conn->query($update_teacher_sql) === TRUE) {
        // Profile updated successfully, redirect to teacher dashboard
        header("Location: teacher_dashboard.php");
        exit;  // Ensure no further script execution
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Teacher Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Update Profile</h1>
            <nav>
                <a href="logout.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Logout</a>
            </nav>
        </div>
    </header>

   <!-- Form Container -->
<div class="max-w-7xl mx-auto p-8 bg-white shadow-md rounded-lg mt-6">
    <form method="POST" action="update_teacher_profile.php">

        <!-- Name Field -->
        <div class="mb-6">
            <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>

        <!-- Email Field -->
        <div class="mb-6">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <!-- Teacher ID Field -->
        <div class="mb-6">
            <label for="teacher_id" class="block text-gray-700 font-bold mb-2">Teacher ID</label>
            <input type="text" id="teacher_id" name="teacher_id" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($user['teacher_id']); ?>" required>
        </div>

        <!-- Department Field -->
        <div class="mb-6">
            <label for="department" class="block text-gray-700 font-bold mb-2">Department</label>
            <select id="department" name="department" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <?php
                // Fetch all departments for the dropdown
                require 'db.php';
                $sql = "SELECT department_code, department_name FROM departments";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $selected = ($row['department_code'] == $department_code) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['department_code']) . "' $selected>" . htmlspecialchars($row['department_name']) . "</option>";
                    }
                } else {
                    echo "<option value=''>No departments available</option>";
                }

                $conn->close();
                ?>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Update Profile
        </button>
    </form>
</div>

</body>
</html>
