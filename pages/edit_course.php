<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: login.html");
    exit;
}

require 'db.php';

$course_id = $_GET['id'];
$sql = "SELECT * FROM courses WHERE id = $course_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    header("Location: manage_courses.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']); 
    $semester = mysqli_real_escape_string($conn, $_POST['semester']); 
    $credit_hours = mysqli_real_escape_string($conn, $_POST['credit_hours']);
    $prerequisites = mysqli_real_escape_string($conn, $_POST['prerequisites']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']); 
    $class_time = mysqli_real_escape_string($conn, $_POST['class_time']);
    $class_days = mysqli_real_escape_string($conn, $_POST['class_days']);
    $seats_available = mysqli_real_escape_string($conn, $_POST['seats_available']);
    $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);

    $sql = "UPDATE courses SET 
        course_code = '$course_code', 
        course_name = '$course_name', 
        department = '$department',  
        semester = '$semester',
        credit_hours = '$credit_hours', 
        prerequisites = '$prerequisites', 
        teacher_id = '$teacher_id',  
        class_time = '$class_time', 
        class_days = '$class_days', 
        seats_available = '$seats_available', 
        room_number = '$room_number' 
        WHERE id = $course_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_courses.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Edit Course</h1>
            <nav>
                <a href="manage_courses.php" class="text-gray-900 hover:text-blue-600 font-bold py-2 px-4">Back to Courses</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 py-12">
        <form method="POST" action="edit_course.php?id=<?php echo $course_id; ?>" class="bg-white shadow-lg rounded-lg p-8">
            <div class="mb-4">
                <label for="course_code" class="block text-gray-700">Course Code</label>
                <input type="text" name="course_code" id="course_code" class="w-full px-4 py-2 border rounded-lg" required value="<?php echo htmlspecialchars($course['course_code']); ?>">
            </div>
            <div class="mb-4">
                <label for="course_name" class="block text-gray-700">Course Name</label>
                <input type="text" name="course_name" id="course_name" class="w-full px-4 py-2 border rounded-lg" required value="<?php echo htmlspecialchars($course['course_name']); ?>">
            </div>
            <div class="mb-4">
                <label for="department" class="block text-gray-700">Department</label>
                <input type="text" name="department" id="department" class="w-full px-4 py-2 border rounded-lg" required value="<?php echo htmlspecialchars($course['department']); ?>">
            </div>
            <div class="mb-4">
                <label for="semester" class="block text-gray-700">Semester</label>
                <input type="text" name="semester" id="semester" class="w-full px-4 py-2 border rounded-lg" required value="<?php echo htmlspecialchars($course['semester']); ?>">
            </div>
            <div class="mb-4">
                <label for="credit_hours" class="block text-gray-700">Credit Hours</label>
                <input type="number" name="credit_hours" id="credit_hours" class="w-full px-4 py-2 border rounded-lg" required value="<?php echo htmlspecialchars($course['credit_hours']); ?>">
            </div>
            <div class="mb-4">
                <label for="prerequisites" class="block text-gray-700">Prerequisites</label>
                <input type="text" name="prerequisites" id="prerequisites" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($course['prerequisites']); ?>">
            </div>
            <div class="mb-4">
                <label for="teacher_id" class="block text-gray-700">Teacher ID</label>
                <input type="text" name="teacher_id" id="teacher_id" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($course['teacher_id']); ?>">
            </div>
            <div class="mb-4">
                <label for="class_time" class="block text-gray-700">Class Time</label>
                <input type="time" name="class_time" id="class_time" class="w-full px-4 py-2 border rounded-lg" required value="<?php echo htmlspecialchars($course['class_time']); ?>">
            </div>
            <div class="mb-4">
                <label for="class_days" class="block text-gray-700">Class Days</label>
                <input type="text" name="class_days" id="class_days" class="w-full px-4 py-2 border rounded-lg" required value="<?php echo htmlspecialchars($course['class_days']); ?>">
            </div>
            <div class="mb-4">
                <label for="seats_available" class="block text-gray-700">Available Seats</label>
                <input type="number" name="seats_available" id="seats_available" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($course['seats_available']); ?>">
            </div>
            <div class="mb-4">
                <label for="room_number" class="block text-gray-700">Room Number</label>
                <input type="text" name="room_number" id="room_number" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($course['room_number']); ?>">
            </div>
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg">Update Course</button>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow mt-12">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center">
            <p class="text-gray-600">&copy; 2024 Course Advising System | Edit Course</p>
        </div>
    </footer>
</body>
</html>
