<?php
    require('../config/db.php');
    $password = $username = '';
    $errors = array('username'=>'','password'=>'');

    if(isset($_POST['submit'])){
        $valid = true;
        if(empty($_POST['username'])){
            $valid = false;
            $errors['username'] = 'Vuiller Entry Nom d\'utilisateur' ;
        }else{
            $username = strtolower(trim($_POST['username']));
        }
        if(empty($_POST['password'])){
            $valid = false;
            $errors['password'] = 'Vuiller Entry Mot de passe' ;
        }else{
            $password = strtolower(trim($_POST['password']));
        }
  
        if($valid){

            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE (nom_utilisateur = :username OR email = :username) AND mot_de_passe = SHA1(:passwords)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':passwords', $password);
            $stmt->execute();
            $user = $stmt->fetch();
            if($user){
                session_start();
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
                header('Location: session_management.php');
                exit();
            } else{
                $errors['username'] = 'Nom d\'utilisateur ou Mot de passe incorrect' ;
            }
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>ITTHINK</title>
    <style>
        body{
            margin: 0;
            padding: 0;
            height: 100vh;
        }
    </style>
</head>
<style>
    form{
        max-width: 400px;
        width: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
</style>
    <body>
        
    <?php include("../includes/headerAuth.php"); ?>

    <div class="w-full h-full flex justify-center items-center">
        <form action="login.php" method="POST" class="bg-black p-4 rounded-lg">
            <label for="username" class="text-yellow-300">Nom d'utilisateur or Email</label>
            <input type="text" name="username" placeholder="Nom d'utilisateur" class="border-2 border-yellow-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
            <div style="color:white"><?=$errors["username"]?></div>
            <label for="password" class="text-yellow-300">Mot de passe</label>
            <input type="password" name="password" placeholder="Mot de passe" class="border-2 border-yellow-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
            <div style="color:white"><?=$errors["password"]?></div>
            <input type="submit" name="submit" value="connecter" class="inline-block text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">
            <div id="error-message"></div>
        </form>
    </div>

    <?php include("../includes/footer.php"); ?>
</body>
</html>