<?php
class User
{
	public $idusuario;
	public $nombre;
	public $correo;
	public $pass;
	public $foto;
	public $sexo;

	public function deleteUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				delete 
				from usuarios 				
				WHERE idusuario=:idusuario");
		$consulta->bindValue(':idusuario', $this->idusuario, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->rowCount();
	}

	public function updateUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				update usuarios
				set nombre = :nombre,
				correo =:correo,
				pass =:pass,
				foto = :foto,
				sexo = :sexo
				WHERE idusuario =:idusuario");
		$consulta->bindValue(':idusuario', $this->idusuario, PDO::PARAM_INT);
		$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':correo', $this->correo, PDO::PARAM_STR);
		$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
		$consulta->bindValue(':sexo', $this->sexo, PDO::PARAM_STR);
		return $consulta->execute();
	}

	public function insertUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				insert into usuarios 
				(nombre,correo,pass,foto,sexo)
				values (:nombre,:correo,:pass,:foto,:sexo)");
		$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
		$consulta->bindValue(':correo', $this->correo, PDO::PARAM_STR);
		$consulta->bindValue(':pass', $this->pass, PDO::PARAM_STR);
		$consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
		$consulta->bindValue(':sexo', $this->sexo, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}


	public static function getAllUsers()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from usuarios");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "user");
	}

	public static function getUserById($userid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from usuarios where idusuario = :idusuario");
		$consulta->bindValue(':idusuario', $userid, PDO::PARAM_INT);
		$consulta->execute();
		$user = $consulta->fetchObject('User');
		return $user;
	}

	public static function getUserDataByEmailAndPassword($email, $password)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select email,password from users where email = :email and password = :password");
		$consulta->bindValue(':password', $password, PDO::PARAM_STR);
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->fetchObject('User');
	}

	public static function userAlreadyExist($email)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select email,password from users where email = :email");
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->rowCount() > 0;
	}
}