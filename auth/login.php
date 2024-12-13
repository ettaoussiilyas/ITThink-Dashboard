<?php
// session_start(); or
//require ('./session.php');
// require '../config/db.php'; // Include your database connection

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $email = $_POST['email'];
//     $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

//     // Prepare and execute the insert query
//     $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
//     if ($stmt->execute([$email, $password])) {
//         $_SESSION['user_id'] = $pdo->lastInsertId(); // Store user ID in session
//         header("Location: dashboard.php"); // Redirect to dashboard
//         exit();
//     } else {
//         echo "Registration failed.";
//     }
// }
?>