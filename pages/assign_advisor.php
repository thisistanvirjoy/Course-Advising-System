<?php
include 'db.php';

// Fetch teachers for dropdown (now joining users table to get the name)
$teachers = $conn->query("SELECT t.id, u.name 
                          FROM teachers t 
                          JOIN users u ON t.user_id = u.id");

// Handle assigning student to advisor
if (isset($_POST['assign_advisor'])) {
    $student_id = $_POST['student_id'];
    $teacher_id = $_POST['teacher_id'];

    // Check if the student already has an advisor in the students table
    $checkExisting = $conn->prepare("SELECT advisor_id FROM students WHERE student_id = ?");
    $checkExisting->bind_param("s", $student_id); // student_id is varchar
    $checkExisting->execute();
    $resultCheck = $checkExisting->get_result();
    $row = $resultCheck->fetch_assoc();

    if ($row['advisor_id'] == NULL) {
        // Assign advisor to student by updating the students table
        $assignQuery = $conn->prepare("UPDATE students SET advisor_id = ? WHERE student_id = ?");
        $assignQuery->bind_param("is", $teacher_id, $student_id); // student_id is varchar
        if ($assignQuery->execute()) {
            $success_message = "Student successfully assigned to the advisor.";
        } else {
            $error_message = "Failed to assign the student.";
        }
    } else {
        $error_message = "This student already has an advisor.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Advisors</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Assign Advisor</h1>
            <nav>
                <a href="manager_dashboard.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Back to Dashboard</a>
            </nav>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Success/Error messages -->
        <?php if (isset($success_message)): ?>
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Assign Advisor Form -->
        <form action="assign_advisor.php" method="POST" class="mb-6">
            <label for="student_id" class="block text-gray-700">Student ID</label>
            <input type="text" name="student_id" id="student_id" class="w-full px-4 py-2 border rounded-lg mb-4" required>

            <label for="teacher_id" class="block text-gray-700">Assign to Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full px-4 py-2 border rounded-lg mb-4" required>
                <?php while ($row = $teachers->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="assign_advisor" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Assign Advisor</button>
        </form>
    </div>

</body>
</html>
