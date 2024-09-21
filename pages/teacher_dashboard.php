<?php
session_start();
require 'db.php';

// Assuming user_id of the teacher is stored in session
$teacher_id = $_SESSION['user_id']; 
// Ensure teacher's name is set in the session
if (!isset($_SESSION['teacher_name'])) {
    // Fetch the teacher's name from the users table if not set
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT users.name FROM users 
            JOIN teachers ON users.id = teachers.user_id 
            WHERE users.id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['teacher_name'] = $row['name'];
    } else {
        echo "Teacher not found!";
    }
}

// Fetch all students assigned to this teacher as their advisor
$sql_students = "
    SELECT s.student_id, u.name as student_name 
    FROM students s
    JOIN users u ON s.user_id = u.id
    WHERE s.advisor_id = (
        SELECT id FROM teachers WHERE user_id = '$teacher_id'
    )
";
$result_students = $conn->query($sql_students);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Welcome, <?php echo $_SESSION['teacher_name']; ?>!</h1>
            <nav>
                <a href="logout.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Logout</a>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-xl font-bold">Teacher's Dashboard</h2>
        <p>Here you can track manager your advised students.</p>
        <?php
        // Define the current semester
            $currentMonth = date('n'); // Numeric representation of a month, without leading zeros (1 through 12)
            $currentYear = date('Y'); // A full numeric representation of a year, 4 digits

            // Determine the semester based on the current month
            if ($currentMonth >= 1 && $currentMonth <= 5) {
                $current_semester = 'Spring ' . $currentYear;
            } elseif ($currentMonth >= 8 && $currentMonth <= 12) {
                $current_semester = 'Autumn ' . $currentYear;
            } else {
                $current_semester = 'Summer ' . $currentYear;
            }
?>  
<h2 class="text-lg font-bold mt-8">Semester: <?php echo $current_semester; ?></h2>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-xl font-bold mb-4">Students Assigned to You</h2>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Student ID</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Student Name</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_students->fetch_assoc()): ?>
                <tr>
                    <td class="py-2 px-4 text-center"><?php echo $row['student_id']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['student_name']; ?></td>
                    <td class="py-2 px-4 text-center">
                        <a href="view_courses.php?student_id=<?php echo $row['student_id']; ?>" class="text-blue-600">View Courses</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
