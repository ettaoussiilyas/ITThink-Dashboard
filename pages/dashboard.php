<?php
session_start();
require('../config/db.php');

// Check if user is logged in
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: login.php');
    exit();
}

// Fetch statistics
$stmt_users = $pdo->query("SELECT COUNT(*) as total_users FROM utilisateurs");
$total_users = $stmt_users->fetch()['total_users'];

$stmt_projects = $pdo->query("SELECT COUNT(*) as total_projects FROM projets");
$total_projects = $stmt_projects->fetch()['total_projects'];

$stmt_freelancers = $pdo->query("SELECT COUNT(*) as total_freelancers FROM freelances");
$total_freelancers = $stmt_freelancers->fetch()['total_freelancers'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../public/css/dashboard.css">
    <title>Dashboard</title>

</head>
<body>
    <?php include("../includes/header.php"); ?>
    
    <section>
        <div class="container">
            <div class="card">
                <h2>Total Users</h2>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="card">
                <h2>Total Projects</h2>
                <p><?php echo $total_projects; ?></p>
            </div>
            <div class="card">
                <h2>Total Freelancers</h2>
                <p><?php echo $total_freelancers; ?></p>
            </div>
        </div>
    </section>

    <div>
        <h3>Recent Activities</h3>
        <!-- Code to display recent projects or offers -->
    </div>

    <div>
        <a href="create_project.php">Create New Project</a>
        <a href="submit_offer.php">Submit Offer</a>
    </div>

    <?php include("../includes/footer.php"); ?>
</body>
</html> 