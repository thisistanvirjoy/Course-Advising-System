<?php
include 'db.php';

// Fetch departments
$sql = "SELECT * FROM departments";
$result = $conn->query($sql);

// Handle form submissions for adding a department
if (isset($_POST['add_department'])) {
    $department_code = mysqli_real_escape_string($conn, $_POST['department_code']);
    $department_name = mysqli_real_escape_string($conn, $_POST['department_name']);
    
    // Ensure department_code is unique
    $sqlCheck = "SELECT COUNT(*) AS count FROM departments WHERE department_code = '$department_code'";
    $checkResult = $conn->query($sqlCheck);
    $checkRow = $checkResult->fetch_assoc();
    
    if ($checkRow['count'] == 0) {
        $sqlInsert = "INSERT INTO departments (department_code, department_name) VALUES ('$department_code', '$department_name')";
        $conn->query($sqlInsert);
    } else {
        echo "Department code already exists.";
    }
    
    header("Location: manage_departments.php");
    exit;
}

// Handle deletion of departments
if (isset($_GET['delete_id'])) {
    $department_id = $_GET['delete_id'];
    $sqlDelete = "DELETE FROM departments WHERE id = $department_id";
    $conn->query($sqlDelete);
    header("Location: manage_departments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Header -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Manage Departments</h1>
        <nav>
            <a href="manager_dashboard.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Back to Dashboard</a>
        </nav>
    </div>
</header>
<div class="max-w-7xl mx-auto p-8">
    <!-- Add Department Form -->
    <form action="manage_departments.php" method="POST" class="mb-6">
        <label for="department_code" class="block text-gray-700">Department Code</label>
        <input type="text" name="department_code" id="department_code" class="w-full px-4 py-2 border rounded-lg mb-4" required>
        <label for="department_name" class="block text-gray-700">Department Name</label>
        <input type="text" name="department_name" id="department_name" class="w-full px-4 py-2 border rounded-lg mb-4" required>
        <button type="submit" name="add_department" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Add Department</button>
    </form>

    <!-- Department Table -->
    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Department Code</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Department Name</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="py-2 px-4"><?php echo $row['department_code']; ?></td>
                <td class="py-2 px-4"><?php echo $row['department_name']; ?></td>
                <td class="py-2 px-4">
                    <a href="manage_departments.php?delete_id=<?php echo $row['id']; ?>" class="text-red-600">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
