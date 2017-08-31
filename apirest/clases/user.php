<?php
class User
{
    public $userid;
    public $password;
    public $email;
    public $photo;
    
    public function deleteUser()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
               delete 
               from users 				
               WHERE userid=:userid");
        $consulta->bindValue(':userid', $this->userid, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }

    public function updateUser()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
               update users 
               set password=:password,
               email=:email,
               photo=:photo
               WHERE userid=:userid");
        $consulta->bindValue(':userid', $this->userid, PDO::PARAM_INT);
        $consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':photo', $this->photo, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function insertUser()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
                insert into users 
                (password,email,photo)
                values(:password,:email,:photo)");
        $consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':photo', $this->photo, PDO::PARAM_STR);
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function getAllUsers()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
                select * from users");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "User");
    }

    public static function getUserById($userid)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("
                select * from users where userid = :userid");
        $consulta->bindValue(':userid', $this->userid, PDO::PARAM_INT);
        $consulta->execute();
        $user = $consulta->fetchObject('User');
        return $user;

    }
}