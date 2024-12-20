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

// Get all testimonials
$query_read = "SELECT t.*, u.nom_utilisateur 
               FROM temoignages t 
               LEFT JOIN utilisateurs u ON t.id_utilisateur = u.id_utilisateur 
               ORDER BY t.id_temoignage DESC";
$result = mysqli_query($connection,$query_read);
$testimonials = mysqli_fetch_all($result,MYSQLI_ASSOC);

//get users for dropdown
$query_users = "SELECT id_utilisateur, nom_utilisateur FROM utilisateurs";
$result_users = mysqli_query($connection,$query_users);
$users = mysqli_fetch_all($result_users,MYSQLI_ASSOC);

//action treatment
if(isset($_POST["action"])){
    switch($_POST["action"]){
        case "add":
            $commentaire = $_POST["commentaire_add"];
            $id_utilisateur = $_POST["user_id_add"];

            $query_add = "INSERT INTO temoignages (commentaire, id_utilisateur)
                          VALUES ('$commentaire', '$id_utilisateur')";
            $result_add = mysqli_query($connection,$query_add);
            if($result_add){
                header("location: testimonials.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
            break;

        case "edit":
            $id_temoignage = $_POST["id_testimonial_edit"];
            $commentaire = $_POST["commentaire_edit"];
            $id_utilisateur = $_POST["user_id_edit"];

            $query_edit = "UPDATE temoignages SET 
                          commentaire = '$commentaire',
                          id_utilisateur = '$id_utilisateur'
                          WHERE id_temoignage = '$id_temoignage'";
            
            $result_edit = mysqli_query($connection, $query_edit);
            if($result_edit){
                header("location: testimonials.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
            break;

        case "delete":
            $idDelete = $_POST["id_delete"];
            $query_delete = "DELETE FROM temoignages WHERE id_temoignage='$idDelete'";
            $result_delete = mysqli_query($connection,$query_delete);
            if($result_delete){
                header("location: testimonials.php");
            }else{
                die("Error: " . mysqli_error($connection));
            }
            break;
    }
}

// Get testimonial data for editing if edit parameter is present
$editTestimonial = null;
if(isset($_GET['edit'])){
    $id_edit = $_GET['edit'];
    $query_get_testimonial = "SELECT * FROM temoignages WHERE id_temoignage='$id_edit'";
    $result_get_testimonial = mysqli_query($connection, $query_get_testimonial);
    $editTestimonial = mysqli_fetch_assoc($result_get_testimonial);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../public/css/categories.css">
    <title>Testimonials Management</title>
</head>
<body class="bg-gray-100">
    <?php include('../includes/sidebar.php')?>
    <div class="main-content">
        <!-- Add testimonial form -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Add Testimonial</h2>
            <form method="POST" class="flex flex-col gap-4" action="testimonials.php">
                <input type="hidden" name="action" value="add">
                <div>
                    <label for="commentaire_add" class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea name="commentaire_add" id="commentaire_add" rows="4" 
                              class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
                <div>
                    <label for="user_id_add" class="block text-sm font-medium text-gray-700">User</label>
                    <select name="user_id_add" id="user_id_add" 
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select a user</option>
                        <?php foreach($users as $user):?>
                            <option value="<?php echo $user['id_utilisateur']; ?>">
                                <?php echo $user['nom_utilisateur']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Add Testimonial</button>
            </form>
        </div>

        <hr class="my-8">
        
        <!-- Testimonials table -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Testimonials List</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($testimonials as $testimonial): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $testimonial['id_temoignage'] ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo $testimonial['commentaire'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $testimonial['nom_utilisateur'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="?edit=<?php echo $testimonial['id_temoignage'] ?>" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors mr-2">Edit</a>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_delete" value="<?php echo $testimonial['id_temoignage']; ?>">
                                    <button type="submit" 
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors"
                                            onclick="return confirm('Are you sure you want to delete this testimonial?')">
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

        <!-- Edit testimonial form -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Edit Testimonial</h2>
            <?php if($editTestimonial): ?>
            <form method="POST" class="flex flex-col gap-4" action="testimonials.php">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_testimonial_edit" value="<?php echo $editTestimonial['id_temoignage']; ?>">
                <div>
                    <label for="commentaire_edit" class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea name="commentaire_edit" id="commentaire_edit" rows="4" 
                              class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required><?php echo $editTestimonial['commentaire']; ?></textarea>
                </div>
                <div>
                    <label for="user_id_edit" class="block text-sm font-medium text-gray-700">User</label>
                    <select name="user_id_edit" id="user_id_edit"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" required>
                        <?php foreach($users as $user):?>
                            <option value="<?php echo $user['id_utilisateur']; ?>"
                                    <?php echo ($user['id_utilisateur'] == $editTestimonial['id_utilisateur']) ? 'selected' : ''; ?>>
                                <?php echo $user['nom_utilisateur']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition-colors">Update Testimonial</button>
            </form>
            <?php else: ?>
            <p class="text-gray-500">Select a testimonial to edit</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>