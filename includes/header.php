<?php
    // Get current page name
    $current_page = basename($_SERVER['PHP_SELF']);
    $is_login_page = $current_page === 'login.php';
    $is_register_page = $current_page === 'register.php';
?>

<nav class="bg-black p-4">
    <ul class="flex space-x-4 justify-center">
        <li><a href="index.php" class="text-yellow-300 hover:text-yellow-500">Accueil</a></li>
        <li><a href="about.php" class="text-yellow-300 hover:text-yellow-500">A propos</a></li>
        <li><a href="contact.php" class="text-yellow-300 hover:text-yellow-500">Contact</a></li>
        <li class="<?php echo $is_login_page ? 'hidden' : ''; ?>">
            <a href="login.php" class="text-yellow-300 hover:text-yellow-500">Connexion</a>
        </li>
        <li class="<?php echo $is_register_page ? 'hidden' : ''; ?>">
            <a href="register.php" class="text-yellow-300 hover:text-yellow-500">Inscription</a>
        </li>
        <li class="<?php echo $is_login_page ? 'hidden' : ''; ?><?php echo $is_register_page ? 'hidden' : ''; ?>">
            <a href="logout.php" class="text-yellow-300 hover:text-yellow-500">Déconnexion</a>
        </li>
    </ul>
</nav>