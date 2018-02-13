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

	public static function getReservedDates()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select reserveddate , locationid
		FROM reservations
		WHERE reserveddate and active = true");
		$consulta->execute();
		return $consulta->fetchAll();

	}
	public static function getReservationsByUserId($ownerid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select r.reserveddate, r.reservationid, r.ownerid, l.description, DATE_FORMAT(r.reserveddate,'%d/%m/%Y') as date, r.guestlist
		FROM reservations as r
		join locations as l on r.locationid = l.locationid
		WHERE reserveddate >= CURRENT_DATE() and ownerid = :ownerid and active = true
		ORDER BY r.reserveddate ASC");
		$consulta->bindValue(':ownerid', $ownerid, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	public static function getReservationsByLocationId($locationid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select r.locationid , r.reserveddate, r.reservationid, r.ownerid, l.description, DATE_FORMAT(r.reserveddate,'%d/%m/%Y') as date, r.guestlist, u.username 
		from reservations as r
		join locations as l on r.locationid = l.locationid
		join users as u on r.ownerid = u.userid
		WHERE reserveddate >= CURRENT_DATE() and r.locationid = :locationid and active = true
		ORDER BY r.reserveddate ASC");
		$consulta->bindValue(':locationid', $locationid, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	public function updateGuestList()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		UPDATE reservations 
		SET guestlist = :guestlist
		WHERE reservationid = :reservationid");
		$consulta->bindValue(':reservationid', $this->reservationId, PDO::PARAM_INT);
		$consulta->bindValue(':guestlist', $this->guestList, PDO::PARAM_STR);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	public static function cancelReservation($reservationId)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				update reservations
				set active = false
				WHERE reservationid =:reservationid");
		$consulta->bindValue(':reservationid', $reservationId, PDO::PARAM_INT);
		return $consulta->execute();
	}

	public static function getReservationsDatesByLocationId($locationid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select reserveddate , locationid
		FROM reservations
		WHERE reserveddate > CURRENT_DATE() and active = true and locationid = :locationid");
		$consulta->bindValue(':locationid', $locationid, PDO::PARAM_INT);
		$consulta->execute();
		return $consulta->fetchAll();
	}

	public static function updateReservationDate($reserveddate, $reservationid)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
				update reservations
				set reserveddate = :reserveddate
				WHERE reservationid =:reservationid");
		$consulta->bindValue(':reservationid', $reservationid, PDO::PARAM_INT);
		$consulta->bindValue(':reserveddate', $reserveddate, PDO::PARAM_STR);
		return $consulta->execute();
	}

	public static function getReservationsCountPerMonthByStatus($monthAndyear, $status)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select count(*)
		from reservations
		where MONTH(reserveddate) = " . $monthAndyear['month'] . " and YEAR(reserveddate) = " . $monthAndyear['year'] . " and active = " . $status);
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_COLUMN, 0);
	}

	public static function getReservationStatusForPDFReport($range,$year,$lastyear)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
		$consulta = $objetoAccesoDato->RetornarConsulta("
		select r.reserveddate as date, l.description as place, r.active as status
		from reservations as r
		join locations as l on r.locationid = l.locationid
		where MONTH(r.reserveddate) in ".$range ." and YEAR(r.reserveddate) in (".$year.", " . $lastyear .")
		order by r.reserveddate");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_ASSOC);
	}

}