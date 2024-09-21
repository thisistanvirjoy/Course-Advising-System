<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
            <nav>
            <a href="logout.php" class="text-white bg-red-600 hover:bg-red-700 font-bold py-2 px-4 rounded-lg">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-xl font-bold mb-6">Manager Dashboard</h2>

        <!-- Manager Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Course Catalogue -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <img src="../assets/img/icons8-catalog-64.png" alt="Course Catalogue Icon" class="mx-auto h-24 w-24">
                <h3 class="mt-6 text-lg font-semibold text-gray-900">Course Catalogue</h3>
                <p class="mt-2 text-gray-600">Manage the list of available courses.</p>
                <a href="manage_courses.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Manage Courses</a>
            </div>

            <!-- Manage Departments -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <img src="../assets/img/icons8-department-64.png" alt="Department Icon" class="mx-auto h-24 w-24">
                <h3 class="mt-6 text-lg font-semibold text-gray-900">Manage Departments</h3>
                <p class="mt-2 text-gray-600">Add, update, or delete departments.</p>
                <a href="manage_departments.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Manage Departments</a>
            </div>

            <!-- Manage Teachers -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <img src="../assets/img/icons8-teacher-64.png" alt="Teacher Icon" class="mx-auto h-24 w-24">
                <h3 class="mt-6 text-lg font-semibold text-gray-900">Manage Teachers</h3>
                <p class="mt-2 text-gray-600">Add, update, or delete teachers.</p>
                <a href="manage_teachers.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Manage Teachers</a>
            </div>

            <!-- Manage Students -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <img src="../assets/img/icons8-student-64.png" alt="Student Icon" class="mx-auto h-24 w-24">
                <h3 class="mt-6 text-lg font-semibold text-gray-900">Manage Students</h3>
                <p class="mt-2 text-gray-600">Add, update, or delete students.</p>
                <a href="manage_students.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Manage Students</a>
            </div>
            <!-- Assign Students to Advisors -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <img src="../assets/img/icons8-advisor-64.png" alt="Assign Advisor Icon" class="mx-auto h-24 w-24">
                <h3 class="mt-6 text-lg font-semibold text-gray-900">Assign Advisors</h3>
                <p class="mt-2 text-gray-600">Assign students to their respective advisors.</p>
                <a href="assign_advisor.php" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg">Assign Advisors</a>
            </div>


            <!-- Department-wise Stats -->
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <img src="../assets/img/icons8-stats-64.png" alt="Stats Icon" class="mx-auto h-24 w-24">
                <h3 class="mt-6 text-lg font-semibold text-gray-900">Department Stats</h3>
                <p class="mt-2 text-gray-600">Get a general overview of department stats.</p>
                <a href="department_stats.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Stats</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow mt-12">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center">
            <p class="text-gray-600">&copy; 2024 Course Advising System | Designed with Tailwind CSS</p>
        </div>
    </footer>
</body>
</html>
