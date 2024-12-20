<?php
// Connection to database
$conn = mysqli_connect('localhost', 'root', '', 'itthink');
// if (!isset($_SESSION['id_utilisateur'])) {
//     header('Location: login.php');
//     exit();
// }
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['add_category']) || isset($_GET['delete'])){
    // Add new category
    if (isset($_POST['add_category'])) {
        $nom_categorie = mysqli_real_escape_string($conn, $_POST['nom_categorie']);
        $sql = "INSERT INTO Categories (nom_categorie) VALUES ('$nom_categorie')";
        mysqli_query($conn, $sql);
    }

    // Delete category
    if (isset($_GET['delete'])) {
        $id = mysqli_real_escape_string($conn, $_GET['delete']);
        $sql = "DELETE FROM Categories WHERE id_categorie = '$id'";
        mysqli_query($conn, $sql);
    }
}
if (isset($_POST['add_sous_category']) || isset($_GET['delete_sous_category'])) {
    if(isset($_POST['add_sous_category'])){
        $nom_sous_categorie = mysqli_real_escape_string($conn, $_POST['nom_sous_categorie']);
        $id_categorie_add = mysqli_real_escape_string($conn, $_POST['id_categorie']);
        $sql = "INSERT INTO sous_categories (nom_sous_categorie, id_categorie) VALUES ('$nom_sous_categorie', '$id_categorie_add')";
        mysqli_query($conn, $sql);
    }
    if(isset($_GET['delete_sous_category'])){
        $id = mysqli_real_escape_string($conn, $_GET['delete_sous_category']);
        $sql = "DELETE FROM sous_categories WHERE id_sous_categorie = '$id'";
        mysqli_query($conn, $sql);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../public/css/categories.css">
</head>
<body class="bg-gray-100">
    <?php include('../includes/sidebar.php') ?>
    
    <div class="main-content">
        <h2 class="text-2xl font-bold mb-6">Gestion of Categories</h2>
        
        <!-- add categorie-->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h5 class="text-xl font-semibold mb-4">Ajouter une catégorie</h5>
            <form method="POST">
                <div class="mb-4">
                    <input type="text" name="nom_categorie" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required placeholder="Nom de catégorie">
                </div>
                <button type="submit" name="add_category" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Ajouter</button>
            </form>
        </div>

        <!-- table category -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom of Categories</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $sql = "SELECT * FROM Categories";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>".$row['id_categorie']."</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>".$row['nom_categorie']."</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>
                                <a href='?delete=".$row['id_categorie']."' class='bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors' onclick='return confirm(\"Are You Sure ?\")'>Supprimer</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2 class="text-2xl font-bold mb-6">Gestion Sous Categories</h2>
        <!-- add sous category -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h5 class="text-xl font-semibold mb-4">Ajouter Sous Catégorie</h5>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nom of Sous Category</label>
                    <input type="text" name="nom_sous_categorie" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Category</label>
                    <select name="id_categorie" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                        <?php
                        $sql = "SELECT * FROM Categories";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='".$row['id_categorie']."'>".$row['nom_categorie']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="add_sous_category" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Ajouter</button>
            </form>
        </div>

        <!-- sous category table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom of Sous Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $sql = "SELECT sc.*, c.nom_categorie FROM Sous_Categories sc 
                           JOIN Categories c ON sc.id_categorie = c.id_categorie";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>".$row['id_sous_categorie']."</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>".$row['nom_sous_categorie']."</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>".$row['nom_categorie']."</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>
                                <a href='?delete_sous_category=".$row['id_sous_categorie']."' class='bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors' onclick='return confirm(\"Are You Sure ?\")'>Supprimer</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>  
        </div>
    </div>
</body>
</html>