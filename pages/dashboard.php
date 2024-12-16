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
    if(isset($_POST["new_data_freelancer"])){
        $id_freelance = mysqli_real_escape_string($connect, $_POST['id_freelance_dalate']);
        $nom_freelance = mysqli_real_escape_string($connect, $_POST['nom_freelance']);
        $competences = mysqli_real_escape_string($connect, $_POST['competences']);
        $id_utilisateur = mysqli_real_escape_string($connect, $_POST['id_utilisateur']);
        
        $update_query = "UPDATE freelances SET 
                        nom_freelance = '$nom_freelance',
                        competences = '$competences',
                        id_utilisateur = '$id_utilisateur'
                        WHERE id_freelance = '$id_freelance'";
                        
        $result = mysqli_query($connect, $update_query);
    
        if($result){
            echo "<script>alert('Updated successfully');</script>";
            header('location: dashboard.php');
        } else {
            echo "<script>alert('Update failed');</script>";
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

    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <div class="p-4">
            <h2 class="text-yellow-300 text-xl font-bold mb-6">IT Thinken</h2>
            <ul class="flex flex-col space-y-4">
                <li><a href="dashboard.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a></li>
                <li><a href="users.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users Management
                </a></li>
                <li><a href="categories.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Categories
                </a></li>
                <li><a href="projects.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Projects
                </a></li>
                <li><a href="freelancers.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Freelancers
                </a></li>
                <li><a href="offers.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Offers
                </a></li>
                <li><a href="testimonials.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    Testimonials
                </a></li>
                <li class="mt-auto"><a href="logout.php" class="text-yellow-300 hover:text-yellow-500 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </a></li>
            </ul>
        </div>
    </nav>


    <div class="main-content ml-64 p-4">
        <div class="container">
            <div class="card bg-pink-200">
                <h2 class="text-sm md:text-xl">Total Users</h2>
                <p class="text-sm md:text-lg"><?php echo $total_users; ?></p>
            </div>
            <div class="card bg-green-200">
                <h2 class="text-sm md:text-xl">Total Projects</h2>
                <p class="text-sm md:text-lg"><?php echo $total_projects; ?></p>
            </div>
            <div class="card bg-purple-200">
                <h2 class="text-sm md:text-xl">Total Freelancers</h2>
                <p class="text-sm md:text-lg"><?php echo $total_freelancers; ?></p>
            </div>
        </div>

        <div class="table-container">
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
                                <button type="button" onclick="openModal(<?=$freelancer['id_freelance']?>, '<?=$freelancer['nom_freelance']?>', '<?=$freelancer['competences']?>', <?=$freelancer['id_utilisateur']?>)" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update</button>
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

        <div>
            <a href="create_project.php">Create New Project</a>
            <a href="submit_offer.php">Submit Offer</a>
        </div>

        <!-- form for update -->
        <div id="updateModal" class="modal">
            <div class="modal-content">
                <div class="container">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="flex flex-col space-y-4">
                        <input type="hidden" name="id_freelance_dalate" id="modal_id_freelance">
                        <input type="text" name="nom_freelance" id="modal_nom" placeholder="Nom Freelance" class="border border-gray-300 p-2 rounded-md">
                        <input type="text" name="competences" id="modal_competences" placeholder="Competences" class="border border-gray-300 p-2 rounded-md">
                        <input type="text" name="id_utilisateur" id="modal_id_user" placeholder="ID Utilisateur" class="border border-gray-300 p-2 rounded-md">
                        <input type="submit" value="Update Now" name="new_data_freelancer" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md">Cancel</button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../public/js/dashboard.js"></script>
    </div>
</body>
</html>