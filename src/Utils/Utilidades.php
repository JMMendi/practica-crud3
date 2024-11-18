<?php

namespace App\Utils;

use App\Db\User;

class Utilidades {
    public static function sanearCadena(string $cadena) : string {
        return htmlspecialchars(trim($cadena));
    }

    public static function longitudCadenaValida(string $nomCampo, string $valor , int $min, int $max) : bool {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["err_$nomCampo"] = "*** ERROR, el campo $nomCampo tiene que tener entre $min y $max caracteres. ***";
            return false;
        }
        return true;
    }

    public static function emailValido(string $email) : bool {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["err_email"] = "*** ERROR, el email introducido no es válido. ***";
            return false;
        }
        return true;
    }

    public static function perfilValido(string $perfil) : bool {
        if (!in_array($perfil, Datos::getPerfiles())) {
            $_SESSION["err_perfil"] = "*** ERROR, el perfil introducido no es válido. ***" ;
            return false;
        }
        return true;
    }

    public static function loginValido(string $email, string $password) : bool {
        $datos = User::loginValido($email,$password); // Esto puede ser, según si es invalido el login o no, o devuelve un bool o un array
        if(!is_array($datos)) {
            $_SESSION['err_login'] = "*** ERROR, El email o la contraseña son inválidos. ***";
            return false;
        } 
        $_SESSION['login'] = $datos;
        return true;
    }

    public static function isCampoDuplicado(string $nomCampo, string $valorCampo, int $id=null) : bool {
        if (User::existeValor($nomCampo, $valorCampo, $id)) {
            $_SESSION["err_$nomCampo"] = "*** ERROR, $valorCampo ya está registrado. ***";
            return true;
        }
        return false;
    }

    public static function pintarErrores(string $nomError) : void {
        if (isset($_SESSION[$nomError])) {
            echo "<p class='mt-2 text-red-500 italic text-sm'>{$_SESSION[$nomError]}</p>";
            unset($_SESSION[$nomError]);
        }
    }
}

