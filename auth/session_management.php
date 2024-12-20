<?php
session_start();

if(!isset($_SESSION['id_utilisateur'])){
    header('location: login.php');
    exit();
}

$connect = mysqli_connect('localhost','root','','itthink');
if(!$connect){
    die('Erreur de connexion : '.mysqli_connect_error());
}

$id_of_user = $_SESSION['id_utilisateur'];
$name_of_user = $_SESSION['nom_utilisateur'];

$query_admin = "SELECT * FROM admin WHERE id_utilisateur = $id_of_user";
$query_freelancer = "SELECT * FROM freelances WHERE id_utilisateur = $id_of_user";

$result_admin = mysqli_query($connect, $query_admin);
$result_freelancer = mysqli_query($connect, $query_freelancer);

// Check for admin first
if($result_admin && mysqli_num_rows($result_admin) > 0){
    header('location: ../pages/dashboard.php');
    exit();
}

// Then check for freelancer
if($result_freelancer && mysqli_num_rows($result_freelancer) > 0){
    header('location: ../freelancer/freelancer.php');
    exit();
}

// If neither admin nor freelancer, then it's a regular user
header('location: ../user/user.php');
exit();
?>