<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role_id'] = $user['role_id'];  // Store role ID (1: Student, 2: Teacher, 3: Manager)

            // Debugging: Check if role ID is correct
            echo "Role ID: " . $user['role_id'] . "<br>";

            // Redirect based on role
            if ($user['role_id'] == 1) {
                // Student logic
                $student_sql = "SELECT * FROM students WHERE user_id = '{$user['id']}'";
                $student_result = $conn->query($student_sql);

                if ($student_result === false) {
                    echo "Error: " . $conn->error;
                } elseif ($student_result->num_rows > 0) {
                    $student = $student_result->fetch_assoc();
                    if ($student['profile_completed'] == 0) {
                        header("Location: update_student_profile.php");
                    } else {
                        header("Location: student_dashboard.php");
                    }
                } else {
                    echo "Student profile not found.";
                }
            } elseif ($user['role_id'] == 2) {
                // Teacher logic
                $teacher_sql = "SELECT * FROM teachers WHERE user_id = '{$user['id']}'";
                $teacher_result = $conn->query($teacher_sql);

                // Debugging: Check if teacher query works
                if ($teacher_result === false) {
                    echo "Teacher SQL Error: " . $conn->error;
                } elseif ($teacher_result->num_rows > 0) {
                    $teacher = $teacher_result->fetch_assoc();

                    // Debugging: Check if profile_completed value is fetched correctly
                    echo "Profile Completed: " . $teacher['profile_completed'] . "<br>";

                    if ($teacher['profile_completed'] == 0) {
                        echo "Redirecting to update_teacher_profile.php";
                        header("Location: update_teacher_profile.php");
                    } else {
                        echo "Redirecting to teacher_dashboard.php";
                        header("Location: teacher_dashboard.php");
                    }
                } else {
                    echo "Teacher profile not found.";
                }
            } elseif ($user['role_id'] == 3) {
                // Manager logic
                header("Location: manager_dashboard.php");
            }
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Email not found.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Course Advising System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Login</h2>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input id="email" name="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input id="password" name="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Login
                    </button>
                    <a href="signup.php" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                        Sign Up
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
