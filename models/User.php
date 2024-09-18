<?php

require_once __DIR__ . '/../../config/config.php';

Class User
{
    public $name;
    public $email;
    public $login;
    public $password;

    public function __construct($name, $email, $login, $password){
        $this->name = $name;
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
    }
     //Valida os campos necessarios para o cadastro de um usuario
    protected function validate($name, $email, $login, $password){

        if(empty($name) || empty($email) || empty($login) || empty($password)){
            return ['status' => 'error', 'message' => 'Preencha todos os campos'];
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return ['status' => 'error', 'message' => 'Email invalido'];
        }
        return ['status' => 'success', 'message' => 'Email valido'];
    }

    

    public function register($conn) : array {
        
        $sql = "INSERT INTO user (name, `email`, login, password) VALUES (:name, :email, :login, :password)";

        $validation = $this->validate($this->name, $this->email, $this->login, $this->password);
        if($validation['status'] === 'error') {
            return $validation;
        }

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':login', $this->login);
            $password = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $password);

            $stmt->execute();

            $lastid = $conn->lastInsertId();

            return ['status' => 'success', 'message' => 'Usário criado com sucesso', 'id' => $lastid];
        } catch(PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao criar usuario', 'error' => $e->getMessage()];
        }
    }

    public function login($conn, $login, $password) : array {
        $sql = "SELECT * FROM user WHERE login = :login";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($user && password_verify($password, $user['password'])) {
                return ['status' => 'success', 'message' => 'Login efetuado com sucesso', 'user' => $user];
            } else {
                return ['status' => 'error', 'message' => 'Login ou senha invalidos'];
            }
        } catch(PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao efetuar login', 'error' => $e->getMessage()];
        }
    }

    public function update($conn, $id, $name, $email, $login, $password) : array {
        $sql = "UPDATE user SET name = :name, `email` = :email, login = :login, password = :password WHERE id = :id";
        
        $validation = $this->validate($name, $email, $login, $password);
        if($validation['status'] === 'error') {
            return $validation;
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return ['status' => 'success', 'message' => 'Usário atualizado com sucesso'];
        } catch(PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao atualizar usuário', 'error' => $e->getMessage()];
        }
    }

    public static function getAllUsers($conn) : array {
        $sql = "SELECT * FROM user";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ['status' => 'success', 'message' => 'Usuários encontrados com sucesso', 'users' => $users];
        } catch(PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao buscar usuários', 'error' => $e->getMessage()];
        }
    }

    public static function getUserById($conn, $id) : array {
        $sql = "SELECT * FROM user WHERE id = :id";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'message' => 'Usário encontrado com sucesso', 'user' => $user];
        } catch(PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao buscar usuário', 'error' => $e->getMessage()];
        }
    }

    public static function getUserByLogin($conn, $login) : array {
        $sql = "SELECT * FROM user WHERE `login` = :login";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->execute();


            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'message' => 'Usário encontrado com sucesso', 'user' => $user];
        } catch(PDOException $e) {
            return ['status' => 'error', 'message' => 'Erro ao buscar usuário', 'error' => $e->getMessage()];
        }
    }

    public static function verify_login(){
        if(!isset($_SESSION['user_id'])){
            $dir = __DIR__ . '../views/login.html';
            header('location: ' . $dir);
        }

    }
}