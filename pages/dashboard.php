<?php
session_start();
$connect = mysqli_connect("localhost","root","","itthink");

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: login.php');
    exit();
}
//freelancers
$query_users = "SELECT COUNT(*) as total_users FROM utilisateurs";
$count_result_users = mysqli_query($connect, $query_users);
$total_users = mysqli_fetch_assoc($count_result_users)['total_users'];
//echo $total_users;

//projects
$query_projects = "SELECT COUNT(*) as total_projects FROM projets";
$count_result_projects = mysqli_query($connect, $query_projects);
$total_projects = mysqli_fetch_assoc($count_result_projects)['total_projects'];
//echo $total_projects;

//users
$query_freelancers ="SELECT COUNT(*) as total_freelancers FROM freelances";
$count_result_freelancers = mysqli_query($connect, $query_freelancers);
$total_freelancers = mysqli_fetch_assoc($count_result_freelancers)['total_freelancers'];
//echo $total_freelancers;

?>
<?php 
    //tables loading and delete and update freelancer:
    //freelances
    $connect = mysqli_connect("localhost","root","","itthink");
    $query_freelancers = "SELECT * FROM freelances";
    $freelancers_result = mysqli_query($connect, $query_freelancers);
    $freelancers = mysqli_fetch_all($freelancers_result,MYSQLI_ASSOC);
    //delete part
    if(isset($_POST["delete"])){
        
        $id_delete = mysqli_real_escape_string($connect, $_POST['id_freelance_delete']);
        $result = mysqli_query($connect, "DELETE FROM freelances WHERE id_freelance = '$id_delete'");
        if($result){
            echo "<script>alert('deleted sec');</script>";
            //echo "dffffffffffff";
            header('location: dashboard.php');
        }else{
            echo "<script> alert('deleted failed');</script>";
        }
    }
    
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
    <?php //include("../includes/header.php"); ?>
    <nav class="bg-black p-4 w-64 h-full fixed">
        <ul class="flex flex-col space-y-4">
            <li><a href="index.php" class="text-yellow-300 hover:text-yellow-500">WebSite</a></li>
            <li><a href="about.php" class="text-yellow-300 hover:text-yellow-500">A propos</a></li>
            <li><a href="contact.php" class="text-yellow-300 hover:text-yellow-500">Contact</a></li>
            <li><a href="login.php" class="text-yellow-300 hover:text-yellow-500">Connexion</a></li>
            <li><a href="register.php" class="text-yellow-300 hover:text-yellow-500">Inscription</a></li>
            <li><a href="logout.php" class="text-yellow-300 hover:text-yellow-500">Déconnexion</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="card">
            <h2 class="text-sm md:text-xl">Total Users</h2>
            <p class="text-sm md:text-lg"><?php echo $total_users; ?></p>
        </div>
        <div class="card">
            <h2 class="text-sm md:text-xl">Total Projects</h2>
            <p class="text-sm md:text-lg"><?php echo $total_projects; ?></p>
        </div>
        <div class="card">
            <h2 class="text-sm md:text-xl">Total Freelancers</h2>
            <p class="text-sm md:text-lg"><?php echo $total_freelancers; ?></p>
        </div>
    </div>


    <section class="Freelancer">
        <div class="container mx-auto p-4">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID Freelance</th>
                    <th class="py-3 px-6 text-left">Nom</th>
                    <th class="py-3 px-6 text-left">Competences</th>
                    <th class="py-3 px-6 text-left">ID User</th>
                    <th class="py-3 px-6 text-left">Update</th>
                    <th class="py-3 px-6 text-left">Delete</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach($freelancers as $freelancer):?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-3 py-6"><?=$freelancer['id_freelance']?></td>
                        <td class="px-3 py-6"><?=$freelancer['nom_freelance']?></td>
                        <td class="px-3 py-6">
                            <ul class="list-disc pl-5">
                                <?php foreach(explode(',' , $freelancer['competences']) as $ing):?>
                                    <li><?=$ing?></li>
                                <?php endforeach ; ?>
                            </ul>
                        </td>
                        <td class="px-3 py-6"><?=$freelancer['id_utilisateur']?></td>
                        <td class="px-3 py-6">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="id_freelance_update" value="<?=$freelancer['id_freelance']?>">
                                <input type="submit" name="update" value="update" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            </form>
                        </td>
                        <td class="px-3 py-6">
                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                <input type="hidden" name="id_freelance_delete" value="<?=$freelancer['id_freelance']?>">
                                <input type="submit" name="delete" value="delete" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </section>

    <div>
        <a href="create_project.php">Create New Project</a>
        <a href="submit_offer.php">Submit Offer</a>
    </div>

    <?php include("../includes/footer.php"); ?>
</body>
</html> 