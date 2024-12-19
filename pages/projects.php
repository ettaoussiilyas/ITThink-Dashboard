<?php
// connection to data base
$connection = mysqli_connect('localhost','root','','itthink');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query_read = "SELECT * FROM projets";
$result = mysqli_query($connection,$query_read);
$projets = mysqli_fetch_all($result,MYSQLI_ASSOC);

//get data of categories
$query_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($connection,$query_categories);
$categories = mysqli_fetch_all($result_categories,MYSQLI_ASSOC);

//get data of sous categories
$query_sub_categories = "SELECT * FROM sous_categories";
$result_sub_categories = mysqli_query($connection,$query_sub_categories);
$sub_categories = mysqli_fetch_all($result_sub_categories,MYSQLI_ASSOC);

//get data of users
$query_users = "SELECT * FROM utilisateurs";
$result_users = mysqli_query($connection,$query_users);
$users = mysqli_fetch_all($result_users,MYSQLI_ASSOC);


//action traitement
if(isset($_POST["action"])){
    switch($_POST["action"]){
        case "add":
            $titre_project = $_POST["title_add"];
            $descreption = $_POST["description_add"];
            $id_categorie = $_POST["categorie_id"];
            $id_sous_categorie = $_POST["sub_categorie_id"];
            $id_utilisateur = $_POST["user_id_add"];
            $date_creation = date('Y-m-d H:i:s');

            $query_add = "INSERT INTO projets (titre_project, descreption, id_categorie, id_sous_categorie, id_utilisateur, date_creation)
                          VALUES ('$titre_project', '$descreption', '$id_categorie', '$id_sous_categorie', '$id_utilisateur', '$date_creation')";
            $result_add = mysqli_query($connection,$query_add);
            if($result_add){
                header("location: projects.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
            break;
        case "delete":
            $idDelete = $_POST["id_delete"];
            $query_delete = "DELETE FROM projets WHERE id_projet='$idDelete'";
            $result_delete = mysqli_query($connection,$query_delete);
            if($result_delete){
                header("location: projects.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../public/css/categories.css">
    <title>Document</title>
</head>
<body class="bg-gray-100">
    <?php include('../includes/sidebar.php')?>
    <div class="main-content">
        <!-- create project form -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Add a Project</h2>
            <form method="POST" class="flex flex-col gap-4" action="projects.php">
                <input type="hidden" name="action" value="add">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Project Title</label>
                    <input type="text" name="title_add" id="title" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <input type="text" name="add" hidden value="add">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description_add" id="description" cols="30" rows="5" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700">Categorie ID</label>
                    <select name="categorie_id" id="categorie_id">
                        <?php foreach($categories as $categorie): ?>
                            <option value="<?php echo $categorie['id_categorie']; ?>"><?php echo $categorie['nom_categorie']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="sub_categorie_id" class="block text-sm font-medium text-gray-700">Sub Categorie ID</label>
                    <select name="sub_categorie_id" id="sub_categorie_id">
                        <?php foreach($sub_categories as $sub_categorie): ?>
                            <option value="<?php echo $sub_categorie['id_sous_categorie']; ?>"><?php echo $sub_categorie['nom_sous_categorie']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
                    <select name="user_id_add" id="user_id_add">
                        <?php foreach($users as $user):?>
                            <option value="<?php echo $user['id_utilisateur']; ?>"><?php echo $user['id_utilisateur']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Add Project</button>
            </form>
        </div>
        <hr class="my-8">
        
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Projects List</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projet ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categorie ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Categorie ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Creation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($projets as $projet): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $projet['id_projet'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo $projet['titre_project'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $projet['descreption'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $projet['id_categorie'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $projet['id_sous_categorie'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $projet['id_utilisateur'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $projet['date_creation'] ?></td>
                            <td>
                                <a href="project.php?edit=<?php echo $projet['id_projet'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors mr-2">Edit</a>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_delete" value="<?php echo $projet['id_projet']; ?>">
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Edit a Project</h2>
            <form method="POST" class="flex flex-col gap-4" action="projects.php">
                <input type="hidden" name="action" value="edit">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Edit Project Title</label>
                    <input type="text" name="title_edit" id="title" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <input type="text" name="edit" hidden value="edit">
                    <label for="description" class="block text-sm font-medium text-gray-700">Edit Description</label>
                    <textarea name="description_edit" id="description" cols="30" rows="5" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700">Categorie ID</label>
                    <select name="categorie_edit" id="categorie_edit">
                        <?php foreach($categories as $categorie): ?>
                            <option value="<?php echo $categorie['id_categorie']; ?>"><?php echo $categorie['nom_categorie']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="sub_categorie_edit" class="block text-sm font-medium text-gray-700">Sub Categorie ID</label>
                    <select name="sub_categorie_edit" id="sub_categorie_edit">
                        <?php foreach($sub_categories as $sub_categorie): ?>
                            <option value="<?php echo $sub_categorie['id_sous_categorie']; ?>"><?php echo $sub_categorie['nom_sous_categorie']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="user_id_edit" class="block text-sm font-medium text-gray-700">User ID</label>
                    <select name="user_id_edit" id="user_id_edit" value="45">
                        <?php foreach($users as $user):?>
                            <option value="<?php echo $user['id_utilisateur']; ?>"><?php echo $user['id_utilisateur']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Add Project</button>
            </form>
        </div>

    </div>
</body>
</html>