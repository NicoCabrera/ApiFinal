<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../composer/vendor/autoload.php';
require_once '/clases/AccesoDatos.php';
require_once '/clases/userApi.php';
require_once '/clases/AutentificadorJWT.php';
require_once '/clases/MWCORS.php';
require_once '/clases/MWparaAutentificar.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);



$app->group('/users', function () {
 
  $this->get('/', \userApi::class . ':getAll')->add(\MWCORS::class . ':enableCORS');
 
})->add(\MWparaAutentificar::class . ':VerificarUsuario')->add(\MWCORS::class . ':enableCORS8080');



$app->run();