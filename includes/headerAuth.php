<?php

    $current_page = basename($_SERVER['PHP_SELF']);

?>   
<nav class="bg-black p-4">
    <ul class="flex space-x-4 justify-center">
        <li><a href="../index.php" class="text-yellow-300 hover:text-yellow-500">Accueil</a></li>
        <li><a href="#" class="text-yellow-300 hover:text-yellow-500">A propos</a></li>
        <li><a href="#" class="text-yellow-300 hover:text-yellow-500">Contact</a></li>
        <li class="text-yellow-300 hover:text-yellow-500">
            <a href="./login.php" class="text-yellow-300 hover:text-yellow-500" <?php if ($current_page == "login.php") echo 'hidden'; ?>>SignIn</a>
        </li>
        <li class="text-yellow-300 hover:text-yellow-500">
            <a href="./register.php" <?php if ($current_page == "register.php") echo 'hidden'; ?>>SignUp</a>
        </li>
    </ul>
</nav>