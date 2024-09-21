<?php
include 'db.php';

// Join the courses table with departments, teachers, and users to fetch additional information
$sql = "SELECT 
    c.id,                 -- Include id for editing and deleting
    c.course_code, 
    c.course_name, 
    d.department_name,   -- Fetches department name from departments table
    u.name AS teacher_name,  -- Fetches teacher's name from users table
    c.semester, 
    c.credit_hours, 
    c.prerequisites, 
    c.class_time, 
    c.class_days, 
    c.seats_available, 
    c.room_number
FROM 
    courses c
JOIN 
    teachers t ON c.teacher_id = t.teacher_id  -- Join with teachers table using teacher_id
JOIN 
    users u ON t.user_id = u.id  -- Fetch teacher's name from users table using user_id
JOIN 
    departments d ON c.department = d.department_code  -- Join with departments table using department_code";

$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Manage Courses</h1>
        <nav>
            <a href="manager_dashboard.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Back to Dashboard</a>
        </nav>
    </div>
</header>
<div class="max-w-7xl mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Course Catalogue</h1>

    <a href="add_course.php" class="bg-blue-600 text-white py-2 px-4 rounded mb-4 inline-block">Add Course</a>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead>
            <tr>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Code</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Name</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Credit Hours</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Prerequisites</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Department</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Semester</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Seats</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Room Number</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Teacher</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Class Time</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Class Days</th>
                <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["course_code"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["course_name"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["credit_hours"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["prerequisites"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["department_name"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["semester"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["seats_available"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["room_number"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["teacher_name"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["class_time"]) . "</td>";
                    echo "<td class='py-2 px-4'>" . htmlspecialchars($row["class_days"]) . "</td>";
                    echo "<td class='py-2 px-4'>
                            <a href='edit_course.php?id=" . urlencode($row['id']) . "' class='text-blue-600'>Edit</a> | 
                            <a href='delete_course.php?id=" . urlencode($row['id']) . "' class='text-red-600'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12' class='text-center py-4'>No courses available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
