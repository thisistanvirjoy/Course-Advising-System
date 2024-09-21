<?php
include 'db.php';

// Determine the current semester
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle manual course addition
    if (isset($_POST['add_course'])) {
        $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
        $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
        $department = mysqli_real_escape_string($conn, $_POST['department']); 
        $semester = mysqli_real_escape_string($conn, $_POST['semester']); // Use the provided semester
        $credit_hours = mysqli_real_escape_string($conn, $_POST['credit_hours']);
        $prerequisites = mysqli_real_escape_string($conn, $_POST['prerequisites']);
        $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']); 
        $class_time = mysqli_real_escape_string($conn, $_POST['class_time']);
        $class_days = mysqli_real_escape_string($conn, $_POST['class_days']);
        $seats_available = mysqli_real_escape_string($conn, $_POST['seats_available']);
        $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
        
        $sql = "INSERT INTO courses (
            course_code, 
            course_name, 
            department, 
            semester, 
            credit_hours, 
            prerequisites, 
            teacher_id, 
            class_time, 
            class_days, 
            seats_available, 
            room_number
        ) VALUES ('$course_code', '$course_name', '$department', '$semester', '$credit_hours', '$prerequisites', '$teacher_id', '$class_time', '$class_days', '$seats_available', '$room_number')";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: manage_courses.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Handle CSV upload
    if (isset($_POST['upload_csv'])) {
        $fileName = $_FILES['csv_file']['tmp_name'];

        if ($_FILES['csv_file']['size'] > 0) {
            $file = fopen($fileName, 'r');

            // Skip the header row
            fgetcsv($file);

            // Insert CSV data into courses table
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                $course_code = mysqli_real_escape_string($conn, $column[0]);
                $course_name = mysqli_real_escape_string($conn, $column[1]);
                $department = mysqli_real_escape_string($conn, $column[2]);
                $semester = mysqli_real_escape_string($conn, $column[3]); // New field
                $credit_hours = mysqli_real_escape_string($conn, $column[4]);
                $prerequisites = mysqli_real_escape_string($conn, $column[5]);
                $teacher_id = mysqli_real_escape_string($conn, $column[6]);
                $class_time = mysqli_real_escape_string($conn, $column[7]);
                $class_days = mysqli_real_escape_string($conn, $column[8]);
                $seats_available = mysqli_real_escape_string($conn, $column[9]);
                $room_number = mysqli_real_escape_string($conn, $column[10]);

                $sqlInsert = "INSERT INTO courses (
                    course_code, 
                    course_name, 
                    department, 
                    semester, 
                    credit_hours, 
                    prerequisites, 
                    teacher_id, 
                    class_time, 
                    class_days, 
                    seats_available, 
                    room_number
                ) VALUES (
                    '$course_code', 
                    '$course_name', 
                    '$department', 
                    '$semester', 
                    '$credit_hours', 
                    '$prerequisites', 
                    '$teacher_id', 
                    '$class_time', 
                    '$class_days', 
                    '$seats_available', 
                    '$room_number'
                )";

                $result = mysqli_query($conn, $sqlInsert);

                if (!$result) {
                    echo "Error importing data from CSV.";
                }
            }
            fclose($file);
            echo "CSV file successfully imported!";
        } else {
            echo "File size is zero.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
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

<div class="max-w-4xl mx-auto p-8">
    <h1 class="text-3xl font-bold mb-6">Add Course</h1>

    <!-- CSV Upload Form -->
    <form action="add_course.php" method="POST" enctype="multipart/form-data" class="mb-8">
        <h2 class="text-xl font-bold mb-4">Upload Courses via CSV</h2>
        <div class="mb-4">
            <label for="csv_file" class="block text-gray-700">Choose CSV File</label>
            <input type="file" name="csv_file" id="csv_file" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <button type="submit" name="upload_csv" class="bg-green-600 text-white px-4 py-2 rounded-lg">Upload CSV</button>
    </form>

    <!-- Manual Course Entry Form -->
    <form method="POST" action="">
        <div class="mb-4">
            <label for="course_code" class="block text-gray-700">Course Code</label>
            <input type="text" name="course_code" id="course_code" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="course_name" class="block text-gray-700">Course Name</label>
            <input type="text" name="course_name" id="course_name" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="department" class="block text-gray-700">Department</label>
            <input type="text" name="department" id="department" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="semester" class="block text-gray-700">Semester</label>
            <input type="text" name="semester" id="semester" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($current_semester); ?>" required>
        </div>
        <div class="mb-4">
            <label for="credit_hours" class="block text-gray-700">Credit Hours</label>
            <input type="number" name="credit_hours" id="credit_hours" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="prerequisites" class="block text-gray-700">Prerequisites</label>
            <input type="text" name="prerequisites" id="prerequisites" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div class="mb-4">
            <label for="teacher_id" class="block text-gray-700">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="w-full px-4 py-2 border rounded-lg">

    <!-- Populate this with teacher options -->
    <?php
// Query to fetch teacher_id from teachers and name from users
$teacherQuery = "
    SELECT t.teacher_id, u.name 
    FROM teachers t
    JOIN users u ON t.user_id = u.id";  // Join with users table to fetch teacher's name

$teacherResult = $conn->query($teacherQuery);

// Loop through the result and generate the <option> elements
while ($teacher = $teacherResult->fetch_assoc()) {
    echo "<option value='" . $teacher['teacher_id'] . "'>" . $teacher['name'] . "</option>";  // Use teacher_id as value
}
?>

</select>

        </div>
        <div class="mb-4">
            <label for="class_time" class="block text-gray-700">Class Time</label>
            <input type="time" name="class_time" id="class_time" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="class_days" class="block text-gray-700">Class Days</label>
            <input type="text" name="class_days" id="class_days" class="w-full px-4 py-2 border rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="seats_available" class="block text-gray-700">Available Seats</label>
            <input type="number" name="seats_available" id="seats_available" class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div class="mb-4">
            <label for="room_number" class="block text-gray-700">Room Number</label>
            <input type="text" name="room_number" id="room_number" class="w-full px-4 py-2 border rounded-lg">
        </div>

        <button type="submit" name="add_course" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Add Course</button>
    </form>
</div>

</body>
</html>
