<?php

namespace App\Db;

use App\Utils\Datos;
use PDO;
use PDOException;

require __DIR__."/../../vendor/autoload.php";

class User extends Conexion{
    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private string $perfil;

    public function create() : void {
        $q = "insert into users (username, password, email, perfil) values (:u, :p, :e, :pe)";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':u' => $this->username,
                ':p' => $this->password,
                ':e' => $this->email,
                ':pe' => $this->perfil
            ]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el create: ".$ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function read(?string $username = null) : array {
        $q = ($username === null) ? "select * from users order by username" : "select id, username, email, perfil from users where username=:u";
        $stmt = parent::getConexion()->prepare($q);

        try {
            ($username === null) ? $stmt->execute() : $stmt->execute([':u' => $username]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el create: ".$ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function update(int $id) : void {
        $q = "update users set username=:u, password=:p, email=:e, perfil=:pe where id <> :i";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':u' => $this->username,
                ':p' => $this->password,
                ':e' => $this->email,
                ':pe' => $this->perfil,
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el update: ".$ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function loginValido(string $email, string $pass) : bool|array {
        $q = "select username, perfil, password from users where email=:e";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':e' => $email,
            ]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el create: ".$ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (count($resultado) == 0) {
            return false;
        } 
        // Si he llegado aquí, el correo está registrado. Controlaré que la contraseña coincide.
        if (!password_verify($pass, $resultado[0]->password)) {
            return false;
        }

        // Si hemos llegado aquí, todo ha ido bien
        return [$resultado[0]->username, $email, $resultado[0]->perfil];
    }

    public static function existeValor(string $nomCampo, string $valor, int $id=null) : bool {
        $q = ($id === null) ? "select count(*) as total from users where $nomCampo=:v" : "select count(*) as total from users where $nomCampo=:v AND id <> $id";
        $stmt = parent::getConexion()->prepare($q);

        try {
            ($id === null) ? $stmt->execute([
                ':v' => $valor
            ]) :
            $stmt->execute([
                ':v' => $valor,
                ':i' => $id
            ])
            ;
        } catch (PDOException $ex) {
            throw new PDOException("Error en el existeValor: ".$ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ)[0]->total;
    }

    // **---------------------------

    public static function crearRegistrosRandom(int $cantidad) : void {
        $faker = \Faker\Factory::create("es_ES");

        for ($i = 0 ; $i < $cantidad ; $i++) {
            $username = $faker->unique()->userName();
            $password = 'secret0';
            $email = $username."@".$faker->freeEmailDomain();
            $perfil = $faker->randomElement(Datos::getPerfiles());

            (new User) 
            ->setUsername($username)
            ->setPassword($password)
            ->setEmail($email)
            ->setPerfil($perfil)
            ->create();
        };
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): self
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of perfil
     */
    public function getPerfil(): string
    {
        return $this->perfil;
    }

    /**
     * Set the value of perfil
     */
    public function setPerfil(string $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }
}