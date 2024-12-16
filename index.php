<?php
    $nom_frelancer_update = $competence_update = $id_user_update = '';
    
    $connect = mysqli_connect('localhost','root','','itthink');
    if($connect){
        echo 'Connexion réussie' , '<br>';
        
    }else{
        echo 'Erreur de connexion';
    }
    $result = mysqli_query($connect,"SELECT * FROM freelances");

    
    $freelacers = mysqli_fetch_all($result , MYSQLI_ASSOC);
    
    mysqli_free_result($result);

    //mysqli_close($connect);
    //$pdo = null;
    //print_r($result); mysqli object()

    //print_r($freelacers);

    // <?php

    //     // ... existing code ...
    //     $stmt = $connect->prepare("SELECT * FROM freelances");
    //     $stmt->execute();
    //     $freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     // ... existing code ...

    //     print_r($freelancers);

    //? >

    if(isset($_POST["delete"])){
        
        $id_delete = mysqli_real_escape_string($connect, $_POST['id_freelance_dalate']);
        $result = mysqli_query($connect, "DELETE FROM freelances WHERE id_freelance = '$id_delete'");
        if($result){
            echo "<script>alert('deleted sec');</script>";
            header('location: index.php');
        }else{
            echo "<script> alert('deleted failed');</script>";
        }
    }
    if(isset($_POST["update"])){

        $id_user_update = mysqli_real_escape_string($connect , $_POST['id_freelance_update']);
        //echo gettype($id_update);
        $result = mysqli_query($connect , "SELECT * FROM freelances WHERE id_freelance = '$id_user_update'");;
        if($result){
            $freelancer = mysqli_fetch_assoc($result);
            //print_r($freelancer);
            $nom_frelancer_update = $freelancer['nom_freelance'];
            $competence_update = $freelancer['competences'];
            $id_user_update = $freelancer['id_utilisateur'];
            $id_freelancer_update = $freelancer['id_freelance'];
        }
    }
    if(isset($_POST["new_data_freelancer"])){

        $id_user_update = mysqli_real_escape_string($connect , $_POST['id_freelance_dalate']);
        //echo gettype($id_update);
        $result = mysqli_query($connect , "UPDATE  freelances set   where id_freelance = '$id_user_update'");;
        if($result){
            $freelancer = mysqli_fetch_assoc($result);
            
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>INDEX FOR TEST</title>
</head>
<body>
    <div class="container mx-auto p-4">
        <table class="min-w-xl6 bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray200 text-gray-600 uppercase text-sm leading-normale">
                    <th class="py-3 px-6 text-left" >ID Freelance</th>
                    <th class="py-3 px-6 text-left" >Nom</th>
                    <th class="py-3 px-6 text-left" >Competences</th>
                    <th class="py-3 px-6 text-left" >ID User</th>
                    <th class="py-3 px-6 text-left" >Update</th>
                    <th class="py-3 px-6 text-left" >Delete</th>
                    
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach($freelacers as $freelancer):?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-3 px-6"><?=$freelancer['id_freelance']?></td>
                        <td class="px-3 px-6"><?=$freelancer['nom_freelance']?></td>
                        <!-- <td class="px-3 px-6">< ?=$freelancer['competences']?></td> -->
                        <td class="px-3 py-6">
                            <ul>
                                <?php foreach(explode(',' , $freelancer['competences']) as $ing):?>
                                    <li><?=$ing?></li>
                                <?php endforeach ; ?>

                            </ul>
                        </td>
                        <td class="px-3 px-6"><?=$freelancer['id_utilisateur']?></td>
                        <td class="px-3 py-6">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="id_freelance_update" value="<?=$freelancer['id_freelance']?>">
                                <input type="submit" name="update" value="update" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            </form>
                        </td>
                        <td class="px-3 py-6">
                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                <input type="hidden" name="id_freelance_dalate" value="<?=$freelancer['id_freelance']?>">
                                <input type="submit" name="delete" value="delete" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="container">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" class="flex flex-col space-y-4 max-w-2xl mx-auto">
            <input type="hidden" name="id_freelance_dalate" value="<?=$id_freelancer_update?>">
            <input type="text" name="nom_freelance" placeholder="Nom Freelance" value="<?= "$nom_frelancer_update" ?>" class="border border-gray-300 p-2 rounded-md">
            <input type="text" name="competences" placeholder="Competences" value="<?="$competence_update"  ?>" class="border border-gray-300 p-2 rounded-md">
            <input type="text" name="id_utilisateur" placeholder="ID Utilisateur" value="<?= "$id_user_update" ?>" class="border border-gray-300 p-2 rounded-md">
            <input type="submit" value="Update Now" name="new_data_freelancer" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
        </form>
    </div>
    <div class="status flex justify-center">
        <?php if(count($freelacers)>=2): ?>
            <p>There are more than 2 freelancers</p>
        <?php else:  ?>
            <p>There are Less Than of 2 freelancers</p>
        <?php endif; ?>
    </div>
</body>
</html>