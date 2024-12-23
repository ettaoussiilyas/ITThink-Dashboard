<?php
// connection to database
$connection = mysqli_connect('localhost','root','','itthink');

// if (!isset($_SESSION['id_utilisateur'])) {
//     header('Location: login.php');
//     exit();
// }

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all offers with related data
$query_read = "SELECT o.*, p.titre_project, u.nom_utilisateur 
               FROM offres o 
               LEFT JOIN projets p ON o.id_projet = p.id_projet
               LEFT JOIN utilisateurs u ON o.id_freelance = u.id_utilisateur 
               ORDER BY o.id_offre DESC";
$result = mysqli_query($connection,$query_read);
$offers = mysqli_fetch_all($result,MYSQLI_ASSOC);

//get freelancers for dropdown
$query_users = "SELECT id_utilisateur, nom_utilisateur FROM utilisateurs";
$result_users = mysqli_query($connection,$query_users);
$users = mysqli_fetch_all($result_users,MYSQLI_ASSOC);

//get projects for dropdown
$query_projects = "SELECT id_projet, titre_project FROM projets";
$result_projects = mysqli_query($connection,$query_projects);
$projects = mysqli_fetch_all($result_projects,MYSQLI_ASSOC);

//action treatment
if(isset($_POST["action"])){
    switch($_POST["action"]){
        case "add":
            $montant = $_POST["montant_add"];
            $delai = $_POST["delai_add"];
            $id_freelance = $_POST["freelance_id_add"];
            $id_projet = $_POST["project_id_add"];

            $query_add = "INSERT INTO offres (montant, delai, id_freelance, id_projet)
                          VALUES ('$montant', '$delai', '$id_freelance', '$id_projet')";
            $query_add = "INSERT INTO offres (montant, delai, id_freelance, id_projet)
                          VALUES ('$montant', '$delai', '$id_freelance', '$id_projet')";
            $result_add = mysqli_query($connection,$query_add);
            if($result_add){
                header("location: offers.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
            break;

        case "edit":
            $id_offre = $_POST["id_offer_edit"];
            $montant = $_POST["montant_edit"];
            $delai = $_POST["delai_edit"];
            $id_freelance = $_POST["freelance_id_edit"];
            $id_projet = $_POST["project_id_edit"];

            $query_edit = "UPDATE offres SET 
                          montant = '$montant',
                          delai = '$delai',
                          id_freelance = '$id_freelance',
                          id_projet = '$id_projet'
                          WHERE id_offre = '$id_offre'";
            
            $result_edit = mysqli_query($connection, $query_edit);
            if($result_edit){
                header("location: offers.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
            break;

        case "delete":
            $idDelete = $_POST["id_delete"];
            $query_delete = "DELETE FROM offres WHERE id_offre='$idDelete'";
            $result_delete = mysqli_query($connection,$query_delete);
            if($result_delete){
                header("location: offers.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
            break;
    }
}

// Get offer data for editing if edit parameter is present
$editOffer = null;
if(isset($_GET['edit'])){
    $id_edit = $_GET['edit'];
    $query_get_offer = "SELECT * FROM offres WHERE id_offre='$id_edit'";
    $result_get_offer = mysqli_query($connection, $query_get_offer);
    $editOffer = mysqli_fetch_assoc($result_get_offer);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../public/css/categories.css">
    <title>Offers Management</title>
</head>
<body class="bg-gray-100">
    <?php include('../includes/sidebar.php')?>
    <div class="main-content">
        <!-- Add offer form -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Add Offer</h2>
            <form method="POST" class="flex flex-col gap-4" action="offers.php">
                <input type="hidden" name="action" value="add">
                <div>
                    <label for="montant_add" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="montant_add" id="montant_add" step="0.01" 
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="delai_add" class="block text-sm font-medium text-gray-700">Deadline (in days)</label>
                    <input type="number" name="delai_add" id="delai_add" min="1"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="freelance_id_add" class="block text-sm font-medium text-gray-700">Freelancer</label>
                    <select name="freelance_id_add" id="freelance_id_add" 
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select a freelancer</option>
                        <?php foreach($users as $user):?>
                            <option value="<?php echo $user['id_utilisateur']; ?>">
                                <?php echo $user['nom_utilisateur']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="project_id_add" class="block text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id_add" id="project_id_add" 
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select a project</option>
                        <?php foreach($projects as $project):?>
                            <option value="<?php echo $project['id_projet']; ?>">
                                <?php echo $project['titre_project']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Add Offer</button>
            </form>
        </div>

        <hr class="my-8">
        
        <!-- Offers table -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Offers List</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freelancer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($offers as $offer): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $offer['id_offre'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $offer['monatant'] ?> DH</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $offer['delai'] ?> days</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $offer['nom_utilisateur'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $offer['titre_project'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="?edit=<?php echo $offer['id_offre'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors mr-2">Edit</a>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_delete" value="<?php echo $offer['id_offre']; ?>">
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this offer?')">
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

        <!-- Edit offer form -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Edit Offer</h2>
            <?php if($editOffer): ?>
            <form method="POST" class="flex flex-col gap-4" action="offers.php">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_offer_edit" value="<?php echo $editOffer['id_offre']; ?>">
                <div>
                    <label for="montant_edit" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="montant_edit" id="montant_edit" step="0.01" value="<?php echo $editOffer['montant']; ?>"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="delai_edit" class="block text-sm font-medium text-gray-700">Deadline (in days)</label>
                    <input type="number" name="delai_edit" id="delai_edit" min="1" value="<?php echo $editOffer['delai']; ?>"
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="freelance_id_edit" class="block text-sm font-medium text-gray-700">Freelancer</label>
                    <select name="freelance_id_edit" id="freelance_id_edit"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                        <?php foreach($users as $user):?>
                            <option value="<?php echo $user['id_utilisateur']; ?>"
                                    <?php echo ($user['id_utilisateur'] == $editOffer['id_freelance']) ? 'selected' : ''; ?>>
                                <?php echo $user['nom_utilisateur']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="project_id_edit" class="block text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id_edit" id="project_id_edit"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                        <?php foreach($projects as $project):?>
                            <option value="<?php echo $project['id_projet']; ?>"
                                    <?php echo ($project['id_projet'] == $editOffer['id_projet']) ? 'selected' : ''; ?>>
                                <?php echo $project['titre_project']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition-colors">Update Offer</button>
            </form>
            <?php else: ?>
            <p class="text-gray-500">Select an offer to edit</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>