<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']); // 1 for Student, 2 for Teacher

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // Insert into the users table
        $sql = "INSERT INTO users (name, email, password, role_id) VALUES ('$name', '$email', '$hashed_password', '$role_id')";

        if ($conn->query($sql) === TRUE) {
            $user_id = $conn->insert_id;  // Get the ID of the newly inserted user

            if ($role_id == 1) {
                // Insert into the students table if the role is Student
                $sql = "INSERT INTO students (user_id, profile_completed) VALUES ('$user_id', 0)";
            } elseif ($role_id == 2) {
                // Insert into the teachers table if the role is Teacher
                $sql = "INSERT INTO teachers (user_id, profile_completed) VALUES ('$user_id', 0)";
            }

            if ($conn->query($sql) === TRUE) {
                echo "Registration successful. You can now log in.";
                header("Location: login.php");  // Redirect to login page after successful sign up
                exit;
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Email already exists.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Course Advising System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">Sign Up</h2>
            <form action="signup.php" method="POST">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
            Name
        </label>
        <input id="name" name="name" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
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
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="role_id">
            Role
        </label>
        <select id="role_id" name="role_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <option value="1">Student</option>
            <option value="2">Teacher</option>
        </select>
    </div>
    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Sign Up
        </button>
        <a href="login.php" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
            Login
        </a>
    </div>
</form>

        </div>
    </div>
</body>
</html>
