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

$query = "SELECT role FROM utilisateurs WHERE id_utilisateur = $id_of_user";
$result = mysqli_query($connect, $query);
$user = mysqli_fetch_assoc($result);

// Check freelancer status
$query_freelancer = "SELECT * FROM freelances WHERE id_utilisateur = $id_of_user";
$result_freelancer = mysqli_query($connect, $query_freelancer);

if($user['role'] === 'admin'){
    header('location: ../admin/freelancers.php');
    exit();
} elseif(mysqli_num_rows($result_freelancer) > 0){
    header('location: ../freelancer/freelancer.php');
    exit();
} else {
    header('location: ../user/user.php');
    exit();
}
?>