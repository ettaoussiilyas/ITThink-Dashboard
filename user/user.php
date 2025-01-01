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

    // Fetch projects
    $query = "SELECT p.*, c.nom_categorie 
             FROM projets p 
             LEFT JOIN categories c ON p.id_categorie = c.id_categorie 
             ORDER BY p.date_creation DESC";
    $result = mysqli_query($connection, $query);
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>User Dashboard - <?php echo htmlspecialchars($user_name); ?></title>
    <style>
      body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php include('../includes/headerUserAuth.php')?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
            <a href="../freelancer/journy.php" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                Start Your Journy
            </a>
        </div>

        <!-- grid of projects -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($projects as $project): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($project['titre_project']); ?>
                            </h3>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                <?php echo htmlspecialchars($project['nom_categorie']); ?>
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4">
                            <?php echo htmlspecialchars(substr($project['descreption'], 0, 10)) . '...'; ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                Created: <?php echo date('M d, Y', strtotime($project['date_creation'])); ?>
                            </span>
                            <a href="./project.php?id=<?php echo $project['id_projet']; ?>" 
                               class="text-yellow-500 hover:text-yellow-600 font-medium">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if(empty($projects)): ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">No projects available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
    <div class="testemonial">

    </div>

    <?php include('../includes/footer.php')?>
</body>
</html>