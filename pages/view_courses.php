<?php
require 'db.php';

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Fetch registered courses for the student
    $sql_courses = "
        SELECT c.course_name, c.course_code, r.id as registration_id, r.status 
        FROM registration r
        JOIN courses c ON r.course_id = c.id
        WHERE r.student_id = '$student_id'
    ";
    $result_courses = $conn->query($sql_courses);
}

// Handle course acceptance or rejection
if (isset($_POST['action']) && isset($_POST['registration_id'])) {
    $registration_id = $_POST['registration_id'];
    $action = $_POST['action'];

    // Update the status based on teacher's action
    $new_status = ($action === 'accept') ? 'accepted' : 'rejected';
    $sql_update = "UPDATE registration SET status = '$new_status' WHERE id = '$registration_id'";
    $conn->query($sql_update);

    // Redirect back to view the updated list
    header("Location: view_courses.php?student_id=$student_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800">
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Manage Registration</h1>
        <nav>
            <a href="teacher_dashboard.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Back to Dashboard</a>
        </nav>
    </div>
</header>

    <div class="max-w-7xl mx-auto p-8">
        <h2 class="text-xl font-bold mb-4">Courses for Student ID: <?php echo $student_id; ?></h2>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Name</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Code</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Status</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_courses->fetch_assoc()): ?>
                <tr>
                    <td class="py-2 px-4 text-center"><?php echo $row['course_name']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['course_code']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo ucfirst($row['status']); ?></td>
                    <td class="py-2 px-4 text-center">
                        <?php if ($row['status'] === 'pending'): ?>
                            <!-- Accept/Reject Form -->
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="registration_id" value="<?php echo $row['registration_id']; ?>">
                                <button type="submit" name="action" value="accept" class="bg-green-500 text-white px-4 py-2 rounded-lg">Accept</button>
                                <button type="submit" name="action" value="reject" class="bg-red-500 text-white px-4 py-2 rounded-lg">Reject</button>
                            </form>
                        <?php else: ?>
                            <!-- If already accepted or rejected, just show the status -->
                            <span class="text-gray-600"><?php echo ucfirst($row['status']); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
