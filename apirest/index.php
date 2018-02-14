<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../composer/vendor/autoload.php';
require '../composer/vendor/paragonie/random_compat/psalm-autoload.php';
require_once '/classes/AccesoDatos.php';
require_once '/classes/userApi.php';
require_once '/classes/reservationApi.php';
require_once '/classes/answerApi.php';
require_once '/classes/AuthJWT.php';
require_once '/classes/MWCORS.php';
require_once '/classes/MWAuth.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);


$app->group('/user', function () {
 
  $this->get('/', \userApi::class . ':getAll');
 
  $this->get('/{userid}', \userApi::class . ':getById');

  $this->post('/add', \userApi::class . ':insert')->add(\MWAuth::class . ':verifyUser');

  $this->post('/delete', \userApi::class . ':delete');

  $this->post('/update', \userApi::class . ':update');

  $this->post('/adminadd', \userApi::class . ':saveUserFromAdminComponent');

  $this->post('/usernames', \userApi::class . ':getAllNamesForAutocomplete');

  $this->post('/filteredusers', \userApi::class . ':usersByFilters');

  $this->post('/answers', \userApi::class . ':getAnswersStats');

  $this->post('/answerstoexcel', \userApi::class . ':getCurrentMonthAnswersToExportExcelFile');

  
})->add(\MWCORS::class . ':enableCORS');

$app->group('/login', function () {
  
   $this->post('/signin', \userApi::class . ':validateUser');
  
   $this->post('/signup', \userApi::class . ':validateRegistrationData');
 
 })->add(\MWCORS::class . ':enableCORS');

 $app->group('/answer', function () {
  
  $this->post('/save', \answerApi::class . ':insert');

})->add(\MWCORS::class . ':enableCORS');

 $app->group('/reservation', function () {
  
  $this->post('/add', \reservationApi::class . ':insert');

  $this->post('/checkreservations', \reservationApi::class . ':getAllReservedDate');

  $this->post('/myreservations', \reservationApi::class . ':getReservationsByUser');

  $this->post('/updatelist', \reservationApi::class . ':updateReservationGuestList');

  $this->post('/reservationsbyattendantid', \reservationApi::class . ':getReservationsByAttendant');

  $this->post('/cancel', \reservationApi::class . ':cancelReservations');

  $this->post('/checkreservationsbylocation', \reservationApi::class . ':getReservationsDatesByLocation');

  $this->post('/updatedate', \reservationApi::class . ':updateDate');

  $this->post('/byactivestatus', \reservationApi::class . ':getReservationsCount');
  
  $this->post('/activestatusforpdfreport', \reservationApi::class . ':getReservationsForPDFResport');

  
})->add(\MWCORS::class . ':enableCORS');


$app->run();