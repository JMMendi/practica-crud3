<?php

use App\Utils\Utilidades;
use App\Db\User;
use App\Utils\Datos;

    session_start();

    if (!isset($_SESSION['login'])) { // Si no está logeado, se va
        header("Location:login.php");
        exit;
    }

    if (!isset($_GET['un'])) { //Si no manda un usuario por get, se va
        header("Location:login.php");
        exit;
    }

    $un = $_GET['un'];
    $username= $_SESSION['login'][0];
    $perfil = $_SESSION['login'][2];

    if ($perfil == 'Normal' && $username != $un) { //Si el perfil es Normal pero se intenta editar un usuario distinto al logeado, se va
        header("Location:login.php");
        exit;
    }
    $ediciónAMiMismo= ($un == $username);

    require __DIR__."/../vendor/autoload.php";

    $usuario = User::read($username)[0];
    $usuarioMostrar = User::read($un)[0];

    $perfiles = Datos::getPerfiles();

    if (isset($_POST['email'])) {
        $username = Utilidades::sanearCadena($_POST['username']);
        $password = Utilidades::sanearCadena($_POST['password']);
        $email = Utilidades::sanearCadena($_POST['email']);
        if ($perfil=='Admin') {
            $perfil = Utilidades::sanearCadena($_POST['perfil']);
        }

        $errores = false;

        if (!Utilidades::emailValido($email)) {
            $errores = true;
        } else {
            if (Utilidades::isCampoDuplicado('email', $email, $usuarioMostrar->id)) {
                $errores = true;  
            }
        }
        if (!Utilidades::longitudCadenaValida('username', $username, 5, 50)) {
            $errores = true;
        } else {
            if (Utilidades::isCampoDuplicado('username', $username, $usuarioMostrar->id)) {
                $errores = true;
            }
        }
        if (!Utilidades::longitudCadenaValida('password', $password, 5, 12) && (strlen($password) != 0)) {
            $errores = true;
        }

        if($perfil == 'Admin') {
            if (!Utilidades::perfilValido($perfil)) {
                $errores = true;
            }
        }

        if ($errores) {
            header("Location:update.php?un=$un");
            exit;
        }
        // Si llegamos hasta aquí, todo bien. Modificamos el usuario
        // El problema ahora es que no escriba una contraseña para no cambiarsela o sí lo quiere modificar

        $perfilActualizado = (isset($_POST['perfil'])) ? $perfil : 'Normal';

        if (strlen($password) != 0) {
            // Aquí, si ha escrito algo en password, se actualizarán todos los campos
            User::update($usuarioMostrar->id, $username, $email, $perfilActualizado, $password);
        } else { 
            // Y aquí si no ha escrito nada
            User::update($usuarioMostrar->id, $username, $email, $perfilActualizado);
        }
        if($ediciónAMiMismo) {
            $_SESSION['login'][0] = $username;
            $_SESSION['login'][1] = $email;
            $_SESSION['login'][2] = $perfilActualizado;

        }
        

        // yyyy vamos a inicio

        $_SESSION['mensaje'] = "Usuario modificado correctamente.";
        header("Location:inicio.php");

        
    }

?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Registro</title>
</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Modificar Usuario (<?= $un ?>)
                    </h1>
                    <form class="space-y-4 md:space-y-6" action="<?php echo $_SERVER['PHP_SELF']."?un=".$un; ?>" method="POST">
                    <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu nombre de usuario:</label>
                            <input type="username" name="username" id="username" value="<?= $usuarioMostrar->username; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tu usuario">
                             <?php Utilidades::pintarErrores('err_username') ?> 
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu correo electrónico</label>
                            <input type="email" name="email" id="email" value="<?= $usuarioMostrar->email; ?>" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tu email">
                             <?php Utilidades::pintarErrores('err_email') ?> 
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <?php Utilidades::pintarErrores('err_password'); ?> 
                            <?php Utilidades::pintarErrores('err_login'); ?>
                        </div>
                        <div>
                            <?php 
                                if(isset($_SESSION['login']) && $_SESSION['login'][2] === 'Admin') {
                                    echo "<div class='mb-4'>";
                                    echo "<label for='perfil' class='block mb-2 text-sm font-medium text-gray-900 dark:text-white'>Perfil</label>";
                                    echo "<select name='perfil' class='bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'";
                                    $cadena = ($usuarioMostrar->perfil == $item) ? "selected" : "";
                                    foreach ($perfiles as $item) {
                                        echo "<option $cadena>{$item}</option>";
                                    }
                                    echo "</select/>";
                                    echo "</div>";
                                }
                            ?>
                        </div>
                        <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-primary-800">Editar</button>
                        <a href="inicio.php" class="block w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-primary-800">Home</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
