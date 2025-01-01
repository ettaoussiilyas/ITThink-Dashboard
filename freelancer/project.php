<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=ITThink", "root", "");
    
    $stmt = $pdo->prepare("
        SELECT p.*, c.nom_categorie, sc.nom_sous_categorie, u.nom_utilisateur,
               f.nom_freelance, o.monatant, o.delai
        FROM Projets p
        JOIN Categories c ON p.id_categorie = c.id_categorie
        JOIN Sous_Categories sc ON p.id_sous_categorie = sc.id_sous_categorie
        JOIN Utilisateurs u ON p.id_utilisateur = u.id_utilisateur
        LEFT JOIN Offres o ON p.id_projet = o.id_projet
        LEFT JOIN Freelances f ON o.id_freelance = f.id_freelance
        WHERE p.id_projet = ?
    ");
    $stmt->execute([$id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        header('Location: index.php');
        exit;
    }
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title><?php echo htmlspecialchars($project['titre_project']); ?></title>
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
    <?php include('../includes/headerFreelancer.php') ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <!-- Project Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <?php echo htmlspecialchars($project['titre_project']); ?>
                    </h1>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                            <?php echo htmlspecialchars($project['nom_categorie']); ?>
                        </span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            <?php echo htmlspecialchars($project['nom_sous_categorie']); ?>
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Posted by: <?php echo htmlspecialchars($project['nom_utilisateur']); ?></p>
                    <p class="text-gray-500 text-sm">
                        <?php echo $project['date_creation'] ? date('F d, Y', strtotime($project['date_creation'])) : 'Date not available'; ?>
                    </p>
                </div>
            </div>

            <!-- Project Description -->
            <div class="prose max-w-none mb-8">
                <p class="text-gray-700 leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($project['descreption'])); ?>
                </p>
            </div>

            <!-- Current Offers Section -->
            <?php if($project['monatant']): ?>
            <div class="border-t pt-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Current Offer</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-gray-600">Freelancer</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($project['nom_freelance']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Amount</p>
                            <p class="font-semibold">$<?php echo number_format($project['monatant'], 2); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Timeline</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($project['delai']); ?> days</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Button -->
            
            <div class="border-t pt-6">
                <a href="./freelancer.php" 
                   class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                    Make an Offer
                </a>
            </div>
            
        </div>
    </div>

    <?php include('../includes/footer.php')?>
</body>
</html>