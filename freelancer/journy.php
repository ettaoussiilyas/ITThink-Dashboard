<?php
    session_start();
    if(!isset($_SESSION['id_utilisateur'])){
        header('location: ../auth/register.php');
        exit();
    }
    
    $user_id = $_SESSION['id_utilisateur'];
    $user_name = $_SESSION['nom_utilisateur'];

    // database connection
    $connection = mysqli_connect('localhost','root','','itthink');
  
    if (!$connection) {
    
        die("Connection failed: " . mysqli_connect_error());
    }


    //get user data
    $user_query = "SELECT * FROM utilisateurs WHERE id_utilisateur = $user_id";
    $user_result = mysqli_query($connection, $user_query);
    $user = mysqli_fetch_assoc($user_result);

    //check if allready freelancer

    $query_check = "SELECT * FROM freelances WHERE id_utilisateur = $user_id";
    $result_check = mysqli_query($connection, $query_check);
    if(mysqli_num_rows($result_check) > 0){
        echo "<script>alert('You Are already a freelancer');</script>";
        echo "<script>window.location.href = '../auth/login.php';</script>";
        // header("location: ../auth/login.php");
        exit();

    }

    // becoming a freelancer
    if(isset($_POST['become_freelancer'])) {
        $competences = mysqli_real_escape_string($connection, $_POST['competences']);
        $nom_freelance = mysqli_real_escape_string($connection, $_POST['nom_freelance']);
        
        $insert_freelancer = "INSERT INTO freelances (nom_freelance, competences, id_utilisateur) 
                     VALUES ({$user['nom_utilisateur']}, '$competences', {$user['id_utilisateur']})";
        
        if(mysqli_query($connection, $insert_freelancer)) {
            $success_message = "Vous êtes maintenant un freelancer!";
            header("Refresh:2"); // refresh page after 2 seconds
            header('location: ./freelancer.php');
            exit();
        } else {
            $error_message = "Erreur lors de l'inscription: " . mysqli_error($connection);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>Commencer votre voyage - <?php echo htmlspecialchars($user_name); ?></title>
</head>
<body class="bg-gray-100">
    <?php include('../includes/headerFreelancer.php') ?>

    <div class="container mx-auto px-4 py-8">
        <?php if(isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

       
        <!-- Become Freelancer Form -->
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Devenir Freelancer</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nom Freelance</label>
                    <input type="text" name="nom_freelance" readonly value="<?php echo $user['nom_utilisateur'];?>"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-yellow-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Compétences (séparées par des virgules)</label>
                    <textarea name="competences" required rows="3"
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-yellow-500"></textarea>
                </div>
                <button type="submit" name="become_freelancer"
                        class="w-full bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 transition duration-300">
                    Devenir Freelancer
                </button>
            </form>
        </div>
       
    </div>


    <?php include('../includes/footer.php') ?>
</body>
</html>