<?php
session_start();

// Connect to database
$conn = mysqli_connect('localhost', 'root', '', 'itthink');
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: login.php');
    exit();
}
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = '';
$success = '';
$users = [];
$editUser = null;


// Handle form submissions (Add, Edit, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            // Add new user
            case 'add':
                // Get form data
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                // Create the query with values directly
                $query = "INSERT INTO Utilisateurs (nom_utilisateur, email, mot_de_passe) 
                         VALUES ('$username', '$email', '$password')";
                
                // Run the query
                if(mysqli_query($conn, $query)) {
                    $success = "User added successfully";
                } else {
                    $error = "Error adding user: " . mysqli_error($conn);
                }
                break;

            // Edit existing user
            case 'edit':
                // Get form data
                $user_id = $_POST['user_id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $role = $_POST['role'];
                
                $query = "UPDATE Utilisateurs 
                        SET nom_utilisateur='$username', email='$email',role='$role' 
                         WHERE id_utilisateur='$user_id'";
                
                // Run the query
                if(mysqli_query($conn, $query)) {
                    $success = "User updated successfully";
                    header("Location: users_management.php");
                    exit();
                } else {
                    $error = "Error updating user: " . mysqli_error($conn);
                }
                break;

            // Delete user
            case 'delete':
                // Get user ID
                $user_id = $_POST['user_id'];
                
                // Create delete query
                $query = "DELETE FROM Utilisateurs WHERE id_utilisateur='$user_id'";
                
                // Run the query
                if(mysqli_query($conn, $query)) {
                    $success = "User deleted successfully";
                } else {
                    $error = "Error deleting user: " . mysqli_error($conn);
                }
                break;
        }
    }
}

// Get user for editing (when edit button is clicked)
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    
    // Get user data for editing
    $query = "SELECT id_utilisateur as id, nom_utilisateur as username, email 
              FROM Utilisateurs WHERE id_utilisateur='$edit_id'";
    $result = mysqli_query($conn, $query);
    $editUser = mysqli_fetch_assoc($result);
}

//users for display in table
$query = "SELECT id_utilisateur as id, nom_utilisateur as username, email,role 
          FROM Utilisateurs ORDER BY id_utilisateur DESC";
$result = mysqli_query($conn, $query);

// save results in array
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// free the result
mysqli_free_result($result);


//Search User
if(isset($_POST['search'])){
    
    $username_search = $_POST['usernamesearch'];
    $query_search = "SELECT id_utilisateur as id, nom_utilisateur as username, email ,'role'
              FROM Utilisateurs WHERE nom_utilisateur='$username_search'";
    $result_search = mysqli_query($conn, $query_search);
    $searchResult = mysqli_fetch_assoc($result_search);
    if(mysqli_num_rows($result_search)==0 ){
        echo '<script>alert("User Not Found");</script>';

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - ITThinken</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="../public/css/categories.css">
</head>
<body class="bg-gray-100">
    <?php include('../includes/sidebar.php') ?>
    
    <div class="main-content">
        <h2 class="text-2xl font-bold mb-6">User Management</h2>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($editUser): ?>
            <!-- Edit User Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Edit User</h3>
                <form method="POST" class="flex gap-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" value="<?php echo $editUser['id']; ?>">
                    <div class="flex-1">
                        <input type="text" name="username" value="<?php echo htmlspecialchars($editUser['username']); ?>" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="flex-1">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($editUser['email']); ?>" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div class="flex-1">
                        <select name="role" id="role" name="role" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="admin">Admin</option>
                            <option value="utilisateur">Utilisateur</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Update User</button>
                        <a href="users_management.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Add User Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Add New User</h3>
                <form method="POST" class="flex gap-4">
                    <input type="hidden" name="action" value="add">
                    <div class="flex-1">
                        <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Username" required>
                    </div>
                    <div class="flex-1">
                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Email" required>
                    </div>
                    <div class="flex-1">
                        <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Password" required>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Add User</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Search Form -->

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Search Users</h3>
            <form method="POST" class="flex gap-4">
                <input type="hidden" name="search" value="search">
                <div class="flex-1">
                    <input type="text" name="usernamesearch" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Username" required>
                </div>
                <div>
                    <button type="submit" name="search" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Search User</button>
                </div>
            </form>
            <?php if(isset($searchResult)): ?>
            <div class="flex gap-4 w-full">
                <div class="flex-1 p-4 bg-gray-50 rounded-lg text-center"><?php echo $searchResult["username"]; ?></div>
                <div class="flex-1 p-4 bg-gray-50 rounded-lg text-center"><?php echo $searchResult["email"]; ?></div>
                <div class="flex-1 p-4 bg-gray-50 rounded-lg text-center"><?php echo $searchResult["id"]; ?></div>
                <div class="flex-1 p-4 bg-gray-50 rounded-lg text-center">
                     <a href="?edit_id= <?php echo $searchResult['id']; ?>" 
                     class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors mr-2">
                      Edit
                     </a>
                </div>
                <div class="flex-1 p-4 bg-gray-50 rounded-lg text-center">
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?php echo $searchResult['id']; ?>">
                        <button type="submit" 
                                class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-colors"
                                onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="?edit_id=<?php echo $user['id']; ?>" 
                                   class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors mr-2">
                                    Edit
                                </a>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
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
    </div>
</body>
</html>