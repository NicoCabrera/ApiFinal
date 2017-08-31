<?php
class User
{
	public $userid;
	public $email;
	public $password;
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
				set email =:email,
				password =:password
				WHERE userid =:userid");
		$consulta->bindValue(':userid', $this->userid, PDO::PARAM_INT);
		$consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
		return $consulta->execute();
	}

	public function insertUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				insert into users 
				(email,password,photo)
				values (:email,:password,:photo)");
		$consulta->bindValue(':email', $this->email, PDO::PARAM_INT);
		$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
		$consulta->bindValue(':photo', $this->photo, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}


	public static function getAllUsers()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "user");
	}

	public static function getUserById($userid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users where userid = :userid");
		$consulta->bindValue(':userid', $userid, PDO::PARAM_INT);
		$consulta->execute();
		$cdBuscado = $consulta->fetchObject('User');
		return $cdBuscado;


	}

}