<?php
class Reservation
{
	public $reservationId;
	public $ownerId;
	public $locationId;
	public $reservedDate;
	public $guestList;

	public function deleteReservation()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				delete 
				from reservations 				
				WHERE reservationid=:reservationid");
		$consulta->bindValue(':reservationid', $this->reservationId, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->rowCount();
	}

	public function updateReservation()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				update reservations
				ownerid =:ownerid,
				locationid =:locationid,
				reserveddate =:reserveddate,
				guestlist = :guestlist,
				WHERE reservationid =:reservationid");
		$consulta->bindValue(':reservationid', $this->reservationId, PDO::PARAM_INT);
		$consulta->bindValue(':ownerid', $this->ownerId, PDO::PARAM_INT);
		$consulta->bindValue(':locationid', $this->locationId, PDO::PARAM_INT);
        $consulta->bindValue(':reserveddate', $this->reservedDate, PDO::PARAM_STR);
        $consulta->bindValue(':guestlist', $this->guestList, PDO::PARAM_STR);
		return $consulta->execute();
	}

	public function insertReservation()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				insert into reservations 
				(ownerid,locationid,reserveddate,guestlist)
				values (:ownerid,:locationid,:reserveddate,:guestlist)");
        $consulta->bindValue(':ownerid', $this->ownerId, PDO::PARAM_INT);
        $consulta->bindValue(':locationid', $this->locationId, PDO::PARAM_INT);
        $consulta->bindValue(':reserveddate', $this->reservedDate, PDO::PARAM_STR);
        $consulta->bindValue(':guestlist', $this->guestList, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}


	public static function getAllReservations()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from reservations");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "reservation");
	}

}