<?php
    session_start();
    if(!isset($_SESSION['id_utilisateur'])){
        header('location: ../auth/login.php');
        exit();
    }
    
    $user_id = $_SESSION['id_utilisateur'];
    $user_name = $_SESSION['nom_utilisateur'];

    // Database connection
    $connection = mysqli_connect('localhost','root','','itthink');
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get freelancer ID
    $freelancer_query = "SELECT id_freelance FROM freelances WHERE id_utilisateur = $user_id";
    $freelancer_result = mysqli_query($connection, $freelancer_query);
    $freelancer = mysqli_fetch_assoc($freelancer_result);
    $freelancer_id = $freelancer ? $freelancer['id_freelance'] : 0; // Set to 0 if not found

    // handle offer submission
    if(isset($_POST['submit_offer']) && $freelancer_id) {
        $project_id = mysqli_real_escape_string($connection, $_POST['project_id']);
        $montant = mysqli_real_escape_string($connection, $_POST['montant']);
        $delai = mysqli_real_escape_string($connection, $_POST['duree']);
        
        $insert_query = "INSERT INTO offres (id_projet, id_freelance, monatant, delai) 
                        VALUES ($project_id, $freelancer_id, $montant, $delai)";
        
        if(mysqli_query($connection, $insert_query)) {
            $success_message = "Votre offre a été soumise avec succès!";
        } else {
            $error_message = "Erreur lors de la soumission de l'offre: " . mysqli_error($connection);
        }
        
    }

    // fatch available projects with their categories and offer counts
    $projects_query = "SELECT 
                        p.*, 
                        c.nom_categorie,
                        COALESCE((
                            SELECT COUNT(*) 
                            FROM offres o 
                            WHERE o.id_projet = p.id_projet
                        ), 0) as nombre_offres,
                        COALESCE((
                            SELECT COUNT(*) 
                            FROM offres o 
                            WHERE o.id_projet = p.id_projet 
                            AND o.id_freelance = $freelancer_id
                        ), 0) as a_deja_offert
                      FROM projets p 
                      LEFT JOIN categories c ON p.id_categorie = c.id_categorie 
                      ORDER BY p.id_projet DESC";

    $projects_result = mysqli_query($connection, $projects_query);
    if (!$projects_result) {
        die("Query failed: " . mysqli_error($connection));
    }
    $projects = mysqli_fetch_all($projects_result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>Freelancer Journey - <?php echo htmlspecialchars($user_name); ?></title>
    <style>
        body{
            min-height: 100vh !important;
            display: flex;
            flex-direction: column;
        }
        .container{
            flex: 1;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include('../includes/headerFreelancer.php') ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Messages -->
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

        <!-- Projects Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Projets Disponibles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($projects as $project): ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    <?php echo htmlspecialchars($project['titre_project']); ?>
                                </h3>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                    <?php echo htmlspecialchars($project['nom_categorie']); ?>
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">
                                <?php echo htmlspecialchars(substr($project['descreption'], 0, 100)) . '...'; ?>
                            </p>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-medium text-blue-600">
                                    <?php echo $project['nombre_offres']; ?> offres
                                </span>
                            </div>
                            <?php if($project['a_deja_offert'] > 0): ?>
                                <div class="bg-gray-100 text-gray-600 text-center py-2 rounded">
                                    Offre déjà soumise
                                </div>
                            <?php else: ?>
                                <button onclick="openOfferModal(<?php echo $project['id_projet']; ?>)" 
                                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                                    Soumettre une offre
                                </button>
                            <?php endif; ?>
                            <a href="./project.php?id=<?php echo $project['id_projet']; ?>" 
                               class="text-yellow-500 hover:text-yellow-600 font-medium">
                                View Details →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if(empty($projects)): ?>
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">Aucun projet disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Offer Modal -->
    <div id="offerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-xl font-bold mb-4">Soumettre une offre</h3>
                <form method="POST" action="">
                    <input type="hidden" name="project_id" id="modal_project_id">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Montant (€)</label>
                        <input type="number" name="montant" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-yellow-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Délai (jours)</label>
                        <input type="number" name="duree" required
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-yellow-500">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeOfferModal()"
                                class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Annuler
                        </button>
                        <button type="submit" name="submit_offer"
                                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Soumettre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openOfferModal(projectId) {
            document.getElementById('modal_project_id').value = projectId;
            document.getElementById('offerModal').classList.remove('hidden');
        }

        function closeOfferModal() {
            document.getElementById('offerModal').classList.add('hidden');
        }
    </script>

    <?php include('../includes/footer.php') ?>
</body>
</html>