<?php 
require('../config/db.php');

if(isset($_POST['submit'])){
    
    $username = strtolower(trim($_POST['username']));
    $email = strtolower(trim($_POST['email']));
    $password_simple = strtolower(trim($_POST['password']));

    if(!empty($username) && !empty($email) && !empty($password_simple)){
        //$password_hashi = password_hash($password_simple, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs VALUES (null, :username, SHA1(:password_simple), :email,'utilisateur')");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_simple', $password_simple);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        header('Location: login.php');
        exit();
    }
}

?>
<script>

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const errorMessageDiv = document.getElementById('error-message');

            form.addEventListener("submit", function(event) {
                let valid = true;
                errorMessageDiv.innerHTML = '';

                const username = form.username.value.trim();
                const email = form.email.value.trim();
                const password = form.password.value.trim();
                if (password.length < 8) {
                    errorMessageDiv.innerHTML = 'Saisir passeword de 8 caractères ou plus.<br>';
                    errorMessageDiv.style.color='white';
                    valid = false;
                }
                if (username === '' || email === '' || password === '') {
                    errorMessageDiv.innerHTML = 'Veuillez remplir tous les champs.<br>';
                    valid = false;
                }
                if (!validateEmail(email)) {
                    errorMessageDiv.style.color='white';
                    errorMessageDiv.innerHTML = 'Veuillez saisir une adresse email valide.<br>';
                    valid = false;
                }

                if (!valid) {
                    event.preventDefault();
                }
            });
        });
        function validateEmail(email){
            const forma =  /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return forma.test(String(email).toLowerCase());
        }
</script>

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
            <form action="register.php" method="POST" class="bg-black p-4 rounded-lg">
                <label for="username" class="text-yellow-300">Nom d'utilisateur</label>
                <input type="text" name="username" placeholder="Nom d'utilisateur" class="border-2 border-yellow-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
                <label for="email" class="text-yellow-300">Email</label>
                <input type="text" name="email" placeholder="example@contact.com" class="border-2 border-yellow-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
                <label for="password" class="text-yellow-300">Mot de passe</label>
                <input type="password" name="password" placeholder="Mot de passe" class="border-2 border-yellow-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
                <input type="submit" name="submit" value="S'inscrire" class="inline-block text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">
                <div id="error-message"></div>
            </form>
        </div>

        <?php include("../includes/footer.php"); ?>
    </body>
</html>