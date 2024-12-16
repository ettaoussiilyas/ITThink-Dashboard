<?php
    $connect = mysqli_connect('localhost','root','','itthink');
    if(!$connect){
        die('Erreur de connexion : '.mysqli_connect_error());
        exit();
    }

    session_start();

    if(!isset($_SESSION['id_utilisateur'])){
        die("Erreur de load Session"); 
        exit();
    }

    $id_of_user = $_SESSION['id_utilisateur'];
    $query_admin = "SELECT * FROM admin WHERE id_utilisateur = $id_of_user";
    $query_freelancer = "SELECT * FROM freelances WHERE id_utilisateur = $id_of_user";

    $result_admin = mysqli_query($connect , $query_admin);
    $result_freelancer = mysqli_query($connect, $query_freelancer);
    //echo $_SESSION['id_utilisateur']; // just to check 
    
    if($result_admin && mysqli_num_rows($result_admin) > 0){
        header('location: ../pages/dashboard.php');
        exit();
    }else if($result_freelancer && mysqli_num_rows($result_freelancer)>0){
        header('location: ../pages/freelancers.php');
        exit();
    }else{
        header('location: ../pages/user.php');
        exit();
    }

    
?>