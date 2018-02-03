<?php
require_once 'reservation.php';
require_once 'IGenericDAO.php';
require_once 'AuthJWT.php';

use Slim\Http\UploadedFile;

class reservationApi extends Reservation implements IGenericDAO
{
    public function getAll($request, $response, $args)
    {
        $reservations = Reservation::getAllReservations();
        $newResponse = $response->withJson($reservations, 200);
        return $newResponse;
    }

    public function insert($request, $response, $args)
    {

        $newReservationData = $request->getParsedBody();
        $payload = AuthJWT::ObtenerPayLoad($newReservationData['jwt']);
        
        $newReservation = new Reservation();
        $newReservation->ownerId = $payload->data->userid;
        $newReservation->locationId = $newReservationData['locationId'];
        $reservedDate = date("Y-m-d",strtotime($newReservationData["reservedDate"]));
        $newReservation->reservedDate = $reservedDate;
        $newReservation->guestList = $newReservationData["guestList"];

        $reservationId = $newReservation->insertReservation();

        $rv = new stdclass();
        $rv->message = "La reserva se ha tomado exitosamente.";
        return $response->withJson($rv, 200);
    }

    public function getById($request, $response, $args){

    }

   	public function delete($request, $response, $args){

    }

   	public function update($request, $response, $args){

    }
}