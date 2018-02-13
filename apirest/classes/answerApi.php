<?php
require_once 'answer.php';
require_once 'IGenericDAO.php';
require_once 'AuthJWT.php';

use Slim\Http\UploadedFile;

class answerApi extends Answer implements IGenericDAO
{
    
    public function insert($request, $response, $args)
    {

        $data = $request->getParsedBody();
        $payload = AuthJWT::ObtenerPayLoad($data['jwt']);

        $newAnswerData = $data['answer'];
        $newAnswer = new Answer();
        $newAnswer->ownerid = $payload->data->userid;
        $newAnswer->creationdate = date("Y-m-d");
        $newAnswer->score = $newAnswerData['score'];
        $newAnswer->information = $newAnswerData['information'];
        $newAnswer->reservationsystem = $newAnswerData['reservationsystem'];
        $newAnswer->chosenday = $newAnswerData['chosenday'];
        $newAnswer->rehire = $newAnswerData['rehire'];
        $newAnswer->partyroomrecommend = $newAnswerData['partyroomrecommend'];
        $newAnswer->soundandilluminationrecommend = $newAnswerData['soundandilluminationrecommend'];
        $newAnswer->crockeryandtablelinenrecommend = $newAnswerData['crockeryandtablelinenrecommend'];
        $newAnswer->cateringrecommend = $newAnswerData['cateringrecommend'];
        $newAnswer->sendoffersbyemail = $newAnswerData['sendoffersbyemail'];
        $newAnswer->suggest = substr($newAnswerData['suggest'], 0, 300);

        $answerid = $newAnswer->insertAnswer();

        $rv = new stdclass();
        $rv->message = "La reserva se ha tomado exitosamente.";
        return $response->withJson($rv, 200);
    }

    public function getAnswersForPDFResport($request, $response, $args)
    {
        $rv = new stdclass();
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = intval($now->format('Y'));
        $lastyear = $year - 1;

        //$rv->data = Reservation::getReservationStatusForPDFReport($dataArray['range'], $year, $lastyear);
        return $response->withJson($rv, 200);
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

    public function getAll($request, $response, $args)
    {

    }

}