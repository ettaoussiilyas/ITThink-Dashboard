<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('./config/db.php'); 
// $connect = mysqli_connect("localhost","root","change06","itthink");
// if($connect){
//     echo "Connexion réussie";
// }else{
//     echo "Erreur de connexion";
// }

// Vérifiez si la requête est une requête AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_users') {
    header('Content-Type: application/json');

    $query = "SELECT id_utilisateur, nom_utilisateur, email FROM utilisateurs"; // Ne pas inclure mot_de_passe
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($utilisateurs);
    exit; // Assurez-vous qu'aucune autre sortie n'est envoyée
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>ITTHINK</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure the body takes at least the full height of the viewport */
        }

        footer {
            margin-top: auto; /* Push the footer to the bottom */
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-black p-4">
        <ul class="flex flex-wrap space-x-4 justify-center">
            <li><a href="index.php" class="text-yellow-300 hover:text-yellow-500">Accueil</a></li>
            <li><a href="about.php" class="text-yellow-300 hover:text-yellow-500">A propos</a></li>
            <li><a href="contact.php" class="text-yellow-300 hover:text-yellow-500">Contact</a></li>
            <li><a href="login.php" class="text-yellow-300 hover:text-yellow-500">Connexion</a></li>
            <li><a href="register.php" class="text-yellow-300 hover:text-yellow-500">Inscription</a></li>
            <li><a href="logout.php" class="text-yellow-300 hover:text-yellow-500">Déconnexion</a></li>
        </ul>
    </nav>
    <div class="container mx-auto p-4">
        <table id="myTable" class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Nom d'utilisateur</th>
                    <th class="py-2 px-4 border">Email</th>
                </tr>
            </thead>
            <tbody>
                <!-- données ajoutées ici par AJAX -->
            </tbody>
        </table>
    </div>

    <footer class="bg-black p-8 text-center">
        <p class="text-yellow-300 mt-4">Copyright &copy; 2024 - <?php echo date('Y'); ?> - Tous droits réservés</p>
        <ul class="flex justify-center space-x-4 mt-4">
            <li><a href="privacy.php" class="text-yellow-300 hover:text-yellow-500">Politique de confidentialité</a></li>
            <li><a href="terms.php" class="text-yellow-300 hover:text-yellow-500">Conditions d'utilisation</a></li>
            <li><a href="help.php" class="text-yellow-300 hover:text-yellow-500">Aide</a></li>
        </ul>
        <div class="flex items-center space-x-2 justify-center mt-4">
            <input type="text" placeholder="Rechercher..." class="border-2 border-yellow-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
            <button type="submit" class="inline-block text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">Rechercher</button>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "ajax": {
                "url": "?action=get_users", // URL pour récupérer les utilisateurs
                "dataSrc": "" 
            },
            "columns": [
                { "data": "id_utilisateur" },
                { "data": "nom_utilisateur" },
                { "data": "email" }
            ]
        });
    });
    </script>
</body>
</html>
