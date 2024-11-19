<?php

use App\Db\User;

session_start();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header("Location:inicio.php");
    exit;
}

// Con esto impedimos que un usuario normal se borre (o a otros). Solo se lo permitimos al admin
if (!isset($_SESSION['login']) || $_SESSION['login'][2] != 'Admin') {
    header("Location:inicio.php");  
    exit;
}

require __DIR__."/../vendor/autoload.php";
// Ahora comprobamos si vamos a borrarnos a nosotros mismos
$usuario = User::getUserById($id)[0];

User::delete($id);

if($usuario->username === $_SESSION['login'][0]) {
    header("Location:logout.php");
    exit;
}

$_SESSION['mensaje'] = "Usuario borrado correctamente.";
header("Location:inicio.php");

