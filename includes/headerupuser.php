<?php
    // Get current page name
    $current_page = basename($_SERVER['PHP_SELF']);
    $is_user_page = $current_page === 'user.php';
    // $is_register_page = $current_page === 'register.php';
?>

<nav class="bg-black p-4">
    <ul class="flex space-x-4 justify-center">
        <li><a href="../user/user.php" class="text-yellow-300 hover:text-yellow-500">Accueil</a></li>
        <li><a href="#" class="text-yellow-300 hover:text-yellow-500">A propos</a></li>
        <li><a href="#" class="text-yellow-300 hover:text-yellow-500">Contact</a></li>
        <li><a href="../auth/logout.php" class="text-yellow-300 hover:text-yellow-500">Déconnexion</a></li>
    </ul>
</nav>