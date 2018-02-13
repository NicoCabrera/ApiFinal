<?php
class User
{
	public $userid;
	public $username;
	public $email;
	public $password;
	public $rolid;

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

	public function deleteEmployee()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				delete 
				from employees				
				WHERE employeeid=:employeeid");
		$consulta->bindValue(':employeeid', $this->userid, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->rowCount();
	}

	public function updateUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				update users
				set
				email =:email,
				password =:password,
				rolid =:rolid,
				username = :username
				WHERE userid =:userid");
		$consulta->bindValue(':userid', $this->userid, PDO::PARAM_INT);
		$consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
		$consulta->bindValue(':rolid', $this->rolid, PDO::PARAM_STR);
		$consulta->bindValue(':username', $this->username, PDO::PARAM_STR);
		return $consulta->execute();
	}

	public function insertUser()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				insert into users 
				(username,email,password,rolid)
				values (:username,:email,:password,:rolid)");
		$consulta->bindValue(':username', $this->username, PDO::PARAM_STR);
		$consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $this->password, PDO::PARAM_STR);
		$consulta->bindValue(':rolid', $this->rolid, PDO::PARAM_INT);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public function insertEmployeeUser($locationid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				insert into employees 
				(employeeid,locationid)
				values (:employeeid,:locationid)");
		$consulta->bindValue(':employeeid', $this->userid, PDO::PARAM_INT);
		$consulta->bindValue(':locationid', $locationid, PDO::PARAM_INT);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public function updateEmployeeUser($locationid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				update employees
				set
				locationid = :locationid
				where employeeid = :employeeid");
		$consulta->bindValue(':employeeid', $this->userid, PDO::PARAM_INT);
		$consulta->bindValue(':locationid', $locationid, PDO::PARAM_INT);
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

	public static function getAllUserNames()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select username from users");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_COLUMN, 0);
	}

	public static function getAllEmails()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select email from users");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_COLUMN, 0);
	}


	public static function getUsersByFilters($filters)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

		$query = "";
		//Si solo tiene el nombre de usuario
		if ($filters['username'] != "" && $filters['email'] == "" && $filters['rolid'] == -1 && $filters['locationid'] == -1) {
			$query = "select u.*, e.* 
					 from users as u
					 left join employees as e on e.employeeid = u.userid
					 where u.username like '%" . $filters['username'] . "%'";
			
		//Si solo tiene el correo electrónico 
		} else if ($filters['username'] == "" && $filters['email'] != "" && $filters['rolid'] == -1 && $filters['locationid'] == -1) {
			$query = "select u.*, e.* 
					 from users as u
					 left join employees as e on e.employeeid = u.userid
					 where u.email like '%" . $filters['email'] . "%'";
		//Si solo tiene el tipo de usuario
		} else if ($filters['username'] == "" && $filters['email'] == "" && $filters['rolid'] != -1 && $filters['locationid'] == -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.rolid = " . $filters['rolid'];
		//Si solo tiene la sucursal
		} else if ($filters['username'] == "" && $filters['email'] == "" && $filters['rolid'] == -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where e.locationid = " . $filters['locationid'];
		//Si tiene username y correo electrónico
		} else if ($filters['username'] != "" && $filters['email'] != "" && $filters['rolid'] == -1 && $filters['locationid'] == -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.email like '%" . $filters['email'] . "%' and u.username like '%" . $filters['username'] . "%'";

		//Si tiene username , correo electrónico y tipo de usuario
		} else if ($filters['username'] != "" && $filters['email'] != "" && $filters['rolid'] != -1 && $filters['locationid'] == -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.email like '%" . $filters['email'] . "%' and u.username like '%" . $filters['username'] . "%' and u.rolid = " . $filters['rolid'];
		//Si tiene tiene username , correo electrónico , tipo de usuario y sucursal
		} else if ($filters['username'] != "" && $filters['email'] != "" && $filters['rolid'] != -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.email like '%" . $filters['email'] . "%' and u.username like '%" . $filters['username'] . "%' and e.locationid = " . $filters['locationid'] . " and u.rolid = " . $filters['rolid'];
		//Si tiene username y tipo
		} else if ($filters['username'] != "" && $filters['email'] == "" && $filters['rolid'] != -1 && $filters['locationid'] == -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.username like '%" . $filters['username'] . "%' and u.rolid = " . $filters['rolid'];
		//Si tiene username y locationid
		} else if ($filters['username'] != "" && $filters['email'] == "" && $filters['rolid'] == -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.username like '%" . $filters['username'] . "%' and e.locationid = " . $filters['locationid'];

		//Si tiene correo y tipo de usuario
		} else if ($filters['username'] == "" && $filters['email'] != "" && $filters['rolid'] != -1 && $filters['locationid'] == -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.email like '%" . $filters['email'] . "%' and u.rolid = " . $filters['rolid'];
		//Si tiene correo y location
		} else if ($filters['username'] == "" && $filters['email'] != "" && $filters['rolid'] == -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where u.email like '%" . $filters['email'] . "%' and e.locationid = " . $filters['locationid'];
		// Si tiene tipo y sucursal
		} else if ($filters['username'] == "" && $filters['email'] == "" && $filters['rolid'] != -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where e.locationid = " . $filters['locationid'] . " and u.rolid = " . $filters['rolid'];
		// Si tiene username, tipo y sucursal
		} else if ($filters['username'] != "" && $filters['email'] == "" && $filters['rolid'] != -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where e.locationid = " . $filters['locationid'] . " and u.rolid = " . $filters['rolid'] . " and u.username like '%" . $filters['username'] . "%'";
		}// Si tiene username, correo y sucursal
		else if ($filters['username'] != "" && $filters['email'] != "" && $filters['rolid'] == -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where e.locationid = " . $filters['locationid'] . " and u.username like '%" . $filters['username'] . "%' and u.email like '%" . $filters['email'] . "%'";
		}//Si tiene tipo, correo y sucursal
		else if ($filters['username'] == "" && $filters['email'] != "" && $filters['rolid'] != -1 && $filters['locationid'] != -1) {
			$query = "select u.*, e.* 
			from users as u
			left join employees as e on e.employeeid = u.userid
			where e.locationid = " . $filters['locationid'] . " and u.email like '%" . $filters['email'] . "%' and e.locationid =" . $filters['locationid'];
		}
		
		$consulta = $objetoAccesoDato->RetornarConsulta($query);
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getUserById($userid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users where userid = :userid");
		$consulta->bindValue(':userid', $userid, PDO::PARAM_INT);
		$consulta->execute();
		$user = $consulta->fetchObject('User');
		return $user;
	}

	public static function getUserDataByEmailAndPassword($email, $password)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users where email = :email and password = :password");
		$consulta->bindValue(':password', $password, PDO::PARAM_STR);
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->fetchObject('User');
	}

	public static function userAlreadyExist($email)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from users where email = :email");
		$consulta->bindValue(':email', $email, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->rowCount() > 0;
	}

	public static function getPermissionsByUserRolId($rolid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select p.description, p.uri from permissionsbyrol as pbr
		join permissions as p on pbr.permissionid = p.permissionid
		join roles as r on pbr.rolid = r.rolid
		where pbr.rolid = :rolid");
		$consulta->bindValue(':rolid', $rolid, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	public static function getLocationId($employeeid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select l.locationid
		from employees as e
		join locations as l on e.locationid = l.locationid
		where e.employeeid = :employeeid");
		$consulta->bindValue(':employeeid', $employeeid, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_COLUMN, 0);
	}

}