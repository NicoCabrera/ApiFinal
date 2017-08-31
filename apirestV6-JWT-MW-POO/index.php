<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../composer/vendor/autoload.php';
require '../composer/vendor/paragonie/random_compat/psalm-autoload.php';
require_once '/classes/AccesoDatos.php';
require_once '/classes/userApi.php';
require_once '/classes/AutentificadorJWT.php';
require_once '/classes/MWCORS.php';
require_once '/classes/MWparaAutentificar.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);


$app->group('/user', function () {
 
  $this->get('/', \userApi::class . ':getAll');
 
  $this->get('/{id}', \userApi::class . ':getById');

  $this->post('/', \userApi::class . ':insert');

  $this->delete('/', \userApi::class . ':delete');

  $this->put('/', \userApi::class . ':update');
     
})->add(\MWparaAutentificar::class . ':VerificarUsuario')->add(\MWCORS::class . ':enableCORS');


$app->run();