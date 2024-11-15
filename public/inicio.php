<?php
    session_start();

    require __DIR__."/../vendor/autoload.php";

    $email = false;
    if (isset($_SESSION['login'])) {
        $username = $_SESSION['login'][0];
        $email = $_SESSION['login'][1];
        $perfil = $_SESSION['login'][2];

    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <!-- CDN sweetalert2 -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="bg-teal-200 p-4">


    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Al-Andalus</span>
            </a>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <?php if(!$email) {
                        echo <<< TXT
                            <li>
                                <a href="register.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500" aria-current="page">Register</a>
                            </li>
                            <li>
                                <a href="login.php" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Login</a>
                            </li>
                        TXT;
                    } else {
                        echo <<< TXT
                            <li>
                                <input type='text' value='$username ($email)' class='p-2  rounded rounded-xl border-2 border-blue-500 italic' readonly/>
                            </li>
                            <li>
                                <a href='logout.php' class='block p-2 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold'>
                                CERRAR SESIÓN
                                </a>
                            </li>
                        TXT;
                    } ?>

                </ul>
            </div>
        </div>
    </nav>

    <h3 class="py-2 text-center text-xl">Listado de Usuarios</h3>

    <div class="m4-4 w-3/4 mx-auto">
        <div class="flex flex-row-reverse mb-2">
            <a href="nuevo.php" class="p-2 rounded-x1 text-white bg-blue-500 hover:bg-blue-800 font-semibold"><i class="fas fa-add mr-2"></i>NUEVO</a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</body>

</html>