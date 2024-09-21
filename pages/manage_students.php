<?php
include 'db.php';

// Fetch students, including name and email from users table, and department name from departments table
$sql = "SELECT students.*, users.name, users.email, departments.department_name 
        FROM students
        JOIN users ON students.user_id = users.id
        JOIN departments ON students.department = departments.department_code";
$result = $conn->query($sql);

// Handle deletion of students
if (isset($_GET['delete_id'])) {
    $student_id = $_GET['delete_id'];
    $sqlDelete = "DELETE FROM students WHERE id = $student_id";
    $conn->query($sqlDelete);
    header("Location: manage_students.php");
    exit;
}

// Fetch departments for student assignment
$departments = $conn->query("SELECT * FROM departments");
$users = $conn->query("SELECT id, name FROM users WHERE id NOT IN (SELECT user_id FROM students)");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Manage Students</h1>
            <nav>
                <a href="manager_dashboard.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Back to Dashboard</a>
            </nav>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-8">


        <!-- Student Table -->
        <h2 class="text-xl font-bold mb-4">Students List</h2>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Student ID</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Name</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Email</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Department</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="py-2 px-4 text-center"><?php echo $row['student_id']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['name']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['email']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['department_name']; ?></td>
                    <td class="py-2 px-4 text-center">
                        <a href="manage_students.php?delete_id=<?php echo $row['id']; ?>" 
                           class="text-red-600 hover:text-red-800 font-bold" 
                           onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
