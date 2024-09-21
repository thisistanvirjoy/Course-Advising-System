<?php
session_start();
require 'db.php';

// Fetch existing user data
$user_id = $_SESSION['user_id'];

// Fetch user information, advisor name, and department name
$sql = "SELECT u.name, u.email, s.student_id, s.profile_photo, s.department AS department_code, s.advisor_id, 
        u2.name AS advisor_name, d.department_name 
        FROM users u
        LEFT JOIN students s ON u.id = s.user_id
        LEFT JOIN teachers t ON s.advisor_id = t.id
        LEFT JOIN users u2 ON t.user_id = u2.id
        LEFT JOIN departments d ON s.department = d.department_code
        WHERE u.id = '$user_id'";

$result = $conn->query($sql);

// Check if the query succeeded
if ($result === false) {
    echo "Error in SQL query: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $student_id = $user['student_id'];  // Fetch student ID
        // Check if department_code is correctly fetched
        if (isset($user['department_code'])) {
            $department_code = $user['department_code'];
        } else {
            echo "No department assigned to this student.";
        }
        $advisor_id = $user['advisor_id'];  // Fetch advisor ID
    } else {
        echo "Error fetching student profile.";
    }
}

// Check if department_code is set before proceeding
if (!isset($department_code)) {
    die("Department information is missing. Please check the student profile.");
}

// Fetch courses for the student's department
$sql_courses = "SELECT c.id, c.course_name, c.course_code, c.credit_hours, c.seats_available, 
                c.room_number, c.class_time, c.class_days, c.prerequisites, c.semester, 
                d.department_name, u.name AS teacher_name
                FROM courses c
                JOIN departments d ON c.department = d.department_code
                JOIN teachers t ON c.teacher_id = t.teacher_id
                JOIN users u ON t.user_id = u.id
                WHERE c.department = '$department_code'";  // Filtering by department

$result_courses = $conn->query($sql_courses);

// Handle course registration logic here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if any courses were selected
    if (isset($_POST['courses']) && !empty($_POST['courses'])) {
        $selected_courses = $_POST['courses'];

        // Prepared statement to register courses for the student
        $sql_register = $conn->prepare("INSERT INTO registration (student_id, course_id, advisor_id, status) 
                                        VALUES (?, ?, ?, 'pending')");
        
        foreach ($selected_courses as $course_id) {
            // Bind student_id, course_id, and advisor_id to the statement
            $sql_register->bind_param("sii", $student_id, $course_id, $advisor_id);

            if ($sql_register->execute() === false) {
                echo "Error registering course: " . $conn->error;
            }
        }
        echo "Courses registered successfully!";
    } else {
        echo "No courses selected.";
    }
}
// Fetch registered courses for the current student
$sql_registered = "SELECT c.course_name, c.course_code, c.credit_hours, c.id AS course_id 
                   FROM registration r
                   JOIN courses c ON r.course_id = c.id
                   WHERE r.student_id = '$student_id'";

$result_registered = $conn->query($sql_registered);

// Fetch the registered courses and their statuses for this student
$sql_notifications = "
    SELECT c.course_name, c.course_code, r.status 
    FROM registration r
    JOIN courses c ON r.course_id = c.id
    WHERE r.student_id = '$student_id'
";
$result_notifications = $conn->query($sql_notifications);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function toggleModal() {
            document.getElementById('profileModal').classList.toggle('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
            <nav>
                <div class="flex items-center space-x-4">
                    <button onclick="toggleCoursesModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg">View Registered Courses</button>
                    <button onclick="toggleModal()" class="rounded-full w-10 h-10 overflow-hidden border-2 border-blue-600">
                        <img src="<?php echo $user['profile_photo'] ? '../assets/img/' . $user['profile_photo'] : '../assets/img/default-avatar.png'; ?>" alt="Profile Picture">
                    </button>
                    <a href="logout.php" class="text-white bg-red-600 hover:bg-red-700 font-bold py-2 px-4 rounded-lg">Logout</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-xl font-bold">Student Dashboard</h2>
        <p>Here you can track your academic progress, get course advising, and register for courses.</p>
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
        <!-- Course Registration Section -->
        <h3 class="text-lg font-bold mt-8">Available Courses for <?php echo $current_semester; ?></h3>
        <form method="POST" action="">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mt-4">
                <thead>
                    <tr>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Code</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Name</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Credit Hours</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Prerequisites</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Seats Available</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Room Number</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Class Time</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Class Days</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Teacher</th>
                        
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_courses->num_rows > 0): ?>
                        <?php while($course = $result_courses->fetch_assoc()): ?>
                            <tr>
                                <td class="py-2 px-4"><?php echo $course['course_code']; ?></td>
                                <td class="py-2 px-4"><?php echo $course['course_name']; ?></td>
                                <td class="py-2 px-4"><?php echo $course['credit_hours']; ?></td>
                                <td class="py-2 px-4"><?php echo $course['prerequisites'] ? $course['prerequisites'] : 'None'; ?></td>
                                <td class="py-2 px-4"><?php echo $course['seats_available']; ?></td>
                                <td class="py-2 px-4"><?php echo $course['room_number']; ?></td>
                                <td class="py-2 px-4"><?php echo date('H:i', strtotime($course['class_time'])); ?></td>
                                <td class="py-2 px-4"><?php echo $course['class_days']; ?></td>
                                <td class="py-2 px-4"><?php echo $course['teacher_name']; ?></td>
                                <td class="py-2 px-4">
                                    <input type="checkbox" name="courses[]" value="<?php echo $course['id']; ?>">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="py-2 px-4 text-center">No courses available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg mt-4">Register for Selected Courses</button>
        </form>
    </main>

<!-- Registered Courses Modal -->
<div id="registeredCoursesModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-2/3 p-6">
        <h2 class="text-xl font-bold mb-4">Registered Courses for <?php echo $current_semester; ?></h2>

        <?php if ($result_registered->num_rows > 0): ?>
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Code</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Name</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Credit Hours</th>
                        <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($registered_course = $result_registered->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo $registered_course['course_code']; ?></td>
                            <td class="py-2 px-4"><?php echo $registered_course['course_name']; ?></td>
                            <td class="py-2 px-4"><?php echo $registered_course['credit_hours']; ?></td>
                            <td class="py-2 px-4">
                                <form action="delete_advised_course.php" method="POST">
                                    <input type="hidden" name="course_id" value="<?php echo $registered_course['course_id']; ?>">
                                    <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded-lg">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No registered courses found for this semester.</p>
        <?php endif; ?>

        <button onclick="toggleCoursesModal()" class="mt-4 bg-red-600 text-white px-4 py-2 rounded-lg">Close</button>
    </div>
</div>


<script>
    function toggleCoursesModal() {
        document.getElementById('registeredCoursesModal').classList.toggle('hidden');
    }
</script>

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-bold mb-4">Profile Details</h2>
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Student ID:</strong> <?php echo $user['student_id']; ?></p>
            <p><strong>Advisor Name:</strong> <?php echo $user['advisor_name'] ? $user['advisor_name'] : 'N/A'; ?></p>

            <div class="mt-4">
                <a href="update_student_profile.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Update Profile</a>
            </div>

            <button onclick="toggleModal()" class="mt-4 bg-red-600 text-white px-4 py-2 rounded-lg">Close</button>
        </div>
    </div>
    <div class="max-w-7xl mx-auto p-8">
        <h2 class="text-xl font-bold mb-4">Your Course Registration Status</h2>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Name</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Course Code</th>
                    <th class="py-2 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_notifications->fetch_assoc()): ?>
                <tr>
                    <td class="py-2 px-4 text-center"><?php echo $row['course_name']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo $row['course_code']; ?></td>
                    <td class="py-2 px-4 text-center"><?php echo ucfirst($row['status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
