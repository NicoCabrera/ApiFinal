<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../composer/vendor/autoload.php';
require '../composer/vendor/paragonie/random_compat/psalm-autoload.php';
require_once '/classes/AccesoDatos.php';
require_once '/classes/userApi.php';
require_once '/classes/reservationApi.php';
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

  $this->post('/delete', \userApi::class . ':delete')->add(\MWAuth::class . ':verifyUser');

  $this->post('/update', \userApi::class . ':update')->add(\MWAuth::class . ':verifyUser');
     
})->add(\MWCORS::class . ':enableCORS');

$app->group('/login', function () {
  
   $this->post('/signin', \userApi::class . ':validateUser');
  
   $this->post('/signup', \userApi::class . ':validateRegistrationData');
 
 })->add(\MWCORS::class . ':enableCORS');

 $app->group('/reservation', function () {
  
  $this->post('/add', \reservationApi::class . ':insert');

})->add(\MWCORS::class . ':enableCORS');


$app->run();