<?php
    session_start();
    $connect = mysqli_connect("localhost","root","","itthink");
    // $connct = new mysqli("localhost","root","","itthink");

    // $connect = new PDO("mysql: host=localhost ; dbname = itthink ; charset = utf8","root","");


    // if (!isset($_SESSION['id_utilisateur'])) {
    //     header('Location: login.php');
    //     exit();
    // }
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
                        
        
        try{
            $result = mysqli_query($connect, $update_query);
            if($result){
                echo "<script>alert('Updated successfully');</script>";
                header('location: dashboard.php');
            } 
        }catch(Exception $e){
            error_log($e->getMessage());
            echo "<script>alert('ID User Not Found Or Deplcate ID');</script>";
        }     
        
            
       
    
        // if($result){
        //     echo "<script>alert('Updated successfully');</script>";
        //     header('location: dashboard.php');
        // } else {
        //     echo "<script>alert('Update failed');</script>";
        // }
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


    <?php include('../includes/sidebar.php') ?>
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

        <!-- form for update -->
        <div id="updateModal" class="modal">
            <div class="modal-content">
                <div class="container" style="display: flex; justify-content: center;">
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