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
        $reservedDate = date("Y-m-d", strtotime($newReservationData["reservedDate"]));
        $newReservation->reservedDate = $reservedDate;
        $newReservation->guestList = $newReservationData["guestList"];

        $reservationId = $newReservation->insertReservation();

        $rv = new stdclass();
        $rv->message = "La reserva se ha tomado exitosamente.";
        return $response->withJson($rv, 200);
    }

    public function getAllReservedDate($request, $response, $args)
    {
        $rv = new stdclass();
        $rv->reservedDates = array();
        $rv->reservedDates["CABA"] = array();
        $rv->reservedDates["SUR"] = array();
        $rv->reservedDates["NORTE"] = array();
        $reservedDates = Reservation::getReservedDates();
        foreach ($reservedDates as &$valor) {
            $date = date_parse($valor['reserveddate']);
            $item = new stdclass();
            $item->year = $date['year'];
            $item->month = $date['month'];
            $item->day = $date['day'];


            if ($valor['locationid'] == 151531) {
                array_push($rv->reservedDates["CABA"], $item);
            } else if ($valor['locationid'] == 265840) {
                array_push($rv->reservedDates["SUR"], $item);
            } else {
                array_push($rv->reservedDates["NORTE"], $item);
            }
            array_push($rv->reservedDates, $item);
        }
        return $response->withJson($rv, 200);
    }

    public function getReservationsByUser($request, $response, $args)
    {
        $rv = new stdclass();
        $data = $request->getParsedBody();
        $payload = AuthJWT::ObtenerPayLoad($data['jwt']);
        $reservations = Reservation::getReservationsByUserId($payload->data->userid);
        $rv->reservations = $reservations;
        return $response->withJson($rv, 200);
    }

    public function updateReservationGuestList($request, $response, $args)
    {
        $rv = new stdclass();
        $data = $request->getParsedBody();
        $reservation = new Reservation();
        $reservation->guestList = $data['guestList'];
        $reservation->reservationId = $data['reservationid'];
        $reservation->updateGuestList();
        $rv->message = "La lista se ha actualizado exitosamente";
        return $response->withJson($rv, 200);
    }

    public function getReservationsByAttendant($request, $response, $args)
    {
        $rv = new stdclass();
        $data = $request->getParsedBody();
        $locationid = $data['locationid'];
        $reservations = Reservation::getReservationsByLocationId($locationid);
        $rv->reservations = $reservations;
        return $response->withJson($rv, 200);
    }

    public function cancelReservations($request, $response, $args)
    {
        $rv = new stdclass();
        $data = $request->getParsedBody();
        $reservationid = $data['reservationid'];
        Reservation::cancelReservation($reservationid);


        $locationid = $data['locationid'];
        $reservations = Reservation::getReservationsByLocationId($locationid);
        $rv->reservations = $reservations;
        return $response->withJson($rv, 200);
    }

    public function getReservationsDatesByLocation($request, $response, $args)
    {
        $rv = new stdclass();
        $rv->reservedDates = array();
        $data = $request->getParsedBody();
        $locationid = $data['locationid'];
        $reservedDates = Reservation::getReservationsDatesByLocationId($locationid);
        foreach ($reservedDates as &$valor) {
            $date = date_parse($valor['reserveddate']);
            $item = new stdclass();
            $item->year = $date['year'];
            $item->month = $date['month'];
            $item->day = $date['day'];
            array_push($rv->reservedDates, $item);
        }
        return $response->withJson($rv, 200);
    }

    public function updateDate($request, $response, $args)
    {
        $rv = new stdclass();
        $data = $request->getParsedBody();
        $reserveddate = date("Y-m-d", strtotime($data["reservedDate"]));
        Reservation::updateReservationDate($reserveddate, $data['reservationId']);
        $rv->message = "La fecha se ha actualizado exitosamente";
        return $response->withJson($rv, 200);
    }

    public function getReservationsCount($request, $response, $args)
    {
        $rv = new stdclass();
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = intval($now->format('Y'));
        $lastyear = $year - 1;
        $dataArray = $this->getLast6Months($month, $year, $lastyear);
        $rv->barChartLabels = $dataArray[1];

        $rv->accepted = array();
        $rv->canceled = array();

        $rv->accepted[0] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][0], "true")[0];
        $rv->accepted[1] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][1], "true")[0];
        $rv->accepted[2] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][2], "true")[0];
        $rv->accepted[3] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][3], "true")[0];
        $rv->accepted[4] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][4], "true")[0];
        $rv->accepted[5] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][5], "true")[0];

        $rv->canceled[0] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][0], "false")[0];
        $rv->canceled[1] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][1], "false")[0];
        $rv->canceled[2] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][2], "false")[0];
        $rv->canceled[3] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][3], "false")[0];
        $rv->canceled[4] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][4], "false")[0];
        $rv->canceled[5] = Reservation::getReservationsCountPerMonthByStatus($dataArray["consulta"][5], "false")[0];
        return $response->withJson($rv, 200);
    }

    public function getReservationsForPDFResport($request, $response, $args)
    {
        $rv = new stdclass();
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = intval($now->format('Y'));
        $lastyear = $year - 1;
        $dataArray = $this->getLast6Months($month, $year, $lastyear);

        $rv->data = Reservation::getReservationStatusForPDFReport($dataArray['range'], $year, $lastyear);
        return $response->withJson($rv, 200);
    }

    public function getLast6Months($month, $year, $lastyear)
    {
        $rv = array();
        $consulta = array();
        $firstValue = "";
        $secondValue = array();;
        switch ($month) {
            case "01":
                $consulta = [
                    0 => ["month" => 8, "year" => $lastyear],
                    1 => [
                        "month" => 9,
                        "year" => $lastyear
                    ],
                    2 => [
                        "month" => 10,
                        "year" => $lastyear
                    ],
                    3 => [
                        "month" => 11,
                        "year" => $lastyear
                    ],
                    4 => [
                        "month" => 12,
                        "year" => $lastyear
                    ],
                    5 => [
                        "month" => 1,
                        "year" => $year
                    ],
                ];
                $firstValue = "(8,9,10,11,12,1)";
                $secondValue = ['Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Ene'];
                break;
            case "02":
                $consulta = [
                    0 => [
                        "month" => 9,
                        "year" => $lastyear
                    ],
                    1 => [
                        "month" => 10,
                        "year" => $lastyear
                    ],
                    2 => [
                        "month" => 11,
                        "year" => $lastyear
                    ],
                    3 => [
                        "month" => 12,
                        "year" => $lastyear
                    ],
                    4 => [
                        "month" => 1,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 2,
                        "year" => $year
                    ],
                ];
                $firstValue = "(9,10,11,12,1,2)";
                $secondValue = ['Sep', 'Oct', 'Nov', 'Dic', 'Ene', 'Feb'];
                break;
            case "03":
                $consulta = [
                    0 => [
                        "month" => 10,
                        "year" => $lastyear
                    ],
                    1 => [
                        "month" => 11,
                        "year" => $lastyear
                    ],
                    2 => [
                        "month" => 12,
                        "year" => $lastyear
                    ],
                    3 => [
                        "month" => 1,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 2,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 3,
                        "year" => $year
                    ],
                ];
                $firstValue = "(10,11,12,1,2,3)";
                $secondValue = ['Oct', 'Nov', 'Dic', 'Ene', 'Feb', 'Mar'];
                break;
            case "04":
                $consulta = [
                    0 => [
                        "month" => 11,
                        "year" => $lastyear
                    ],
                    1 => [
                        "month" => 12,
                        "year" => $lastyear
                    ],
                    2 => [
                        "month" => 1,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 2,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 3,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 4,
                        "year" => $year
                    ],
                ];
                $firstValue = "(11,12,1,2,3,4)";
                $secondValue = ['Nov', 'Dic', 'Ene', 'Feb', 'Mar', 'Abr'];
                break;
            case "05":
                $consulta = [
                    0 => [
                        "month" => 12,
                        "year" => $lastyear
                    ],
                    1 => [
                        "month" => 1,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 2,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 3,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 4,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 5,
                        "year" => $year
                    ],
                ];
                $firstValue = "(12,1,2,3,4,5)";
                $secondValue = ['Dic', 'Ene', 'Feb', 'Mar', 'Abr', 'May'];
                break;
            case "06":
                $consulta = [
                    0 => [
                        "month" => 1,
                        "year" => $year
                    ],
                    1 => [
                        "month" => 2,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 3,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 4,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 5,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 6,
                        "year" => $year
                    ],
                ];
                $firstValue = "(1,2,3,4,5,6)";
                $secondValue = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'];
                break;
            case "07":
                $consulta = [
                    0 => [
                        "month" => 2,
                        "year" => $year
                    ],
                    1 => [
                        "month" => 3,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 4,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 5,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 6,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 7,
                        "year" => $year
                    ],
                ];
                $firstValue = "(2,3,4,5,6,7)";
                $secondValue = ['Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul'];
                break;
            case "08":
                $consulta = [
                    0 => [
                        "month" => 3,
                        "year" => $year
                    ],
                    1 => [
                        "month" => 4,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 5,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 6,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 7,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 8,
                        "year" => $year
                    ],
                ];
                $firstValue = "(3,4,5,6,7,8)";
                $secondValue = ['Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago'];
                break;
            case "09":
                $consulta = [
                    0 => [
                        "month" => 4,
                        "year" => $year
                    ],
                    1 => [
                        "month" => 5,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 6,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 7,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 8,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 9,
                        "year" => $year
                    ],
                ];
                $firstValue = "(4,5,6,7,8,9)";
                $secondValue = ['Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep'];
                break;
            case "10":
                $consulta = [
                    0 => [
                        "month" => 5,
                        "year" => $year
                    ],
                    1 => [
                        "month" => 6,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 7,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 8,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 9,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 10,
                        "year" => $year
                    ],
                ];
                $firstValue = "(5,6,7,8,9,10)";
                $secondValue = ['May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct'];
                break;
            case "11":
                $consulta = [
                    0 => [
                        "month" => 6,
                        "year" => $year
                    ],
                    1 => [
                        "month" => 7,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 8,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 9,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 10,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 11,
                        "year" => $year
                    ],
                ];
                $firstValue = "(6,7,8,9,10,11)";
                $secondValue = ['Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov'];
                break;
            case "12":
                $consulta = [
                    0 => [
                        "month" => 7,
                        "year" => $year
                    ],
                    1 => [
                        "month" => 8,
                        "year" => $year
                    ],
                    2 => [
                        "month" => 9,
                        "year" => $year
                    ],
                    3 => [
                        "month" => 10,
                        "year" => $year
                    ],
                    4 => [
                        "month" => 11,
                        "year" => $year
                    ],
                    5 => [
                        "month" => 12,
                        "year" => $year
                    ],
                ];
                $firstValue = "(7,8,9,10,11,12)";
                $secondValue = ['Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                break;
            default:
                break;
        }
        $rv["consulta"] = $consulta;
        $rv[1] = $secondValue;
        $rv['range'] = $firstValue;
        return $rv;
    }

    public function getById($request, $response, $args)
    {

    }

    public function delete($request, $response, $args)
    {

    }

    public function update($request, $response, $args)
    {

    }
}