<?php

    
    $connect = mysqli_connect('localhost','root','','itthink');
    if($connect){
        echo 'Connexion réussie' , '<br>';
        
    }else{
        echo 'Erreur de connexion';
    }
    $result = mysqli_query($connect,"SELECT * FROM freelances");

    
    $freelacers = mysqli_fetch_all($result , MYSQLI_ASSOC);
    
    mysqli_free_result($result);

    mysqli_close($connect);
    //$pdo = null;
    //print_r($result); mysqli object()

    print_r($freelacers);

    // <?php

    //     // ... existing code ...
    //     $stmt = $connect->prepare("SELECT * FROM freelances");
    //     $stmt->execute();
    //     $freelancers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     // ... existing code ...

    //     print_r($freelancers);

    //? >


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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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