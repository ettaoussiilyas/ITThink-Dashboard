<?php
require_once '../auth/session_management.php';
require_once '../includes/db_connection.php';

// Ensure user is logged in and has appropriate permissions
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID not provided']);
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_GET['id']);
$sql = "SELECT id_utilisateur as id, nom_utilisateur as username, email 
        FROM Utilisateurs WHERE id_utilisateur = ?";
        
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
