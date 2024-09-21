<?php
include 'db.php';

// Fetch teachers and join with the user and department tables
$sql = "SELECT teachers.id, teachers.teacher_id, users.name, users.email, departments.department_name 
        FROM teachers
        JOIN users ON teachers.user_id = users.id
        JOIN departments ON teachers.department = departments.department_code";
$result = $conn->query($sql);

// Handle form submissions for adding a teacher
if (isset($_POST['add_teacher'])) {
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);  // Assuming user_id is referenced from users table
    $department_code = mysqli_real_escape_string($conn, $_POST['department_code']);
    
    // Ensure unique teacher_id
    $sqlCheckTeacherId = "SELECT COUNT(*) AS count FROM teachers WHERE teacher_id = '$teacher_id'";
    $checkTeacherIdResult = $conn->query($sqlCheckTeacherId);
    $checkTeacherIdRow = $checkTeacherIdResult->fetch_assoc();
    
    if ($checkTeacherIdRow['count'] == 0) {
        $sqlInsert = "INSERT INTO teachers (teacher_id, user_id, department) 
                      VALUES ('$teacher_id', '$user_id', '$department_code')";
        $conn->query($sqlInsert);
    } else {
        echo "Teacher ID already exists.";
    }
    
    header("Location: manage_teachers.php");
    exit;
}

// Handle deletion of teachers
if (isset($_GET['delete_id'])) {
    $teacher_id = $_GET['delete_id'];
    $sqlDelete = "DELETE FROM teachers WHERE id = $teacher_id";
    if ($conn->query($sqlDelete) === TRUE) {
        // Optionally, also delete from `users` table if needed, based on your application logic.
        // $conn->query("DELETE FROM users WHERE id = $user_id"); // Uncomment if user should be deleted too
        header("Location: manage_teachers.php?message=deleted");
        exit;
    } else {
        echo "Error deleting teacher: " . $conn->error;
    }
}

// Fetch departments for teacher assignment
$departments = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teachers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Manage Teachers</h1>
            <nav>
                <a href="manager_dashboard.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Back to Dashboard</a>
            </nav>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-8">
        <!-- Teacher Table -->
        <h2 class="text-xl font-bold mb-4">Teachers List</h2>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Teacher ID</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Name</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Email</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Department</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="py-2 px-4 text-center"><?php echo $row['teacher_id']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['name']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['email']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['department_name']; ?></td>
                    <td class="py-2 px-4 text-center">
                        <a href="manage_teachers.php?delete_id=<?php echo $row['id']; ?>" 
                           class="text-red-600 hover:text-red-800 font-bold" 
                           onclick="return confirm('Are you sure you want to delete this teacher?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

