<?php
session_start();
require 'db.php';

// Fetch existing user data
$user_id = $_SESSION['user_id'];

// Fetch user information and department name
$sql = "SELECT u.name, u.email, s.student_id, s.profile_photo, s.department AS department_code, d.department_name 
        FROM users u
        LEFT JOIN students s ON u.id = s.user_id
        LEFT JOIN departments d ON s.department = d.department_code
        WHERE u.id = '$user_id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $department_code = $user['department_code'];
    $department_name = $user['department_name'];
} else {
    echo "Error fetching student profile.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $profile_photo = ''; // You can handle file uploads here if needed

    // Update the users table (name and email)
    $update_user_sql = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";

    // Update the students table (student_id and profile_photo)
    $update_student_sql = "UPDATE students SET student_id='$student_id', department='$department', profile_photo='$profile_photo', profile_completed=1 WHERE user_id='$user_id'";

    if ($conn->query($update_user_sql) === TRUE && $conn->query($update_student_sql) === TRUE) {
        echo "Profile updated successfully.";
        // Redirect to student dashboard
        header("Location: student_dashboard.php");
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
    <title>Update Student Profile</title>
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
    <form method="POST" action="update_student_profile.php" enctype="multipart/form-data">

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

        <!-- Student ID Field -->
        <div class="mb-6">
            <label for="student_id" class="block text-gray-700 font-bold mb-2">Student ID</label>
            <input type="text" id="student_id" name="student_id" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($user['student_id']); ?>" required>
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

        <!-- Profile Photo Upload -->
        <div class="mb-6">
            <label for="profile_photo" class="block text-gray-700 font-bold mb-2">Profile Photo</label>
            <input type="file" id="profile_photo" name="profile_photo" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Update Profile
        </button>
    </form>
</div>

    </div>

</body>
</html>
