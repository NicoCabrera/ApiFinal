<?php
require_once 'user.php';
require_once 'IGenericDAO.php';

use Slim\Http\UploadedFile;

class userApi extends User implements IGenericDAO
{
    public function getById($request, $response, $args)
    {
        $userid = $args['userid'];
        $user = User::getUserById($userid);
        if (!$user) {
            $rv = new stdclass();
            $rv->message = "Recurso no encontrado";
            $newResponse = $response->withJson($rv, 404);
        } else {
            $newResponse = $response->withJson($user, 200);
        }
        return $newResponse;
    }

    public function getAll($request, $response, $args)
    {
        $users = User::getAllUsers();
        $newResponse = $response->withJson($users, 200);
        return $newResponse;
    }

    public function insert($request, $response, $args)
    {

        $newUserData = $request->getParsedBody();
        $password = crypt($newUserData["password"], "1af324D");

        $newUser = new User();
        $newUser->username = $newUserData['username'];
        $newUser->email = $newUserData["email"];
        $newUser->password = $password;
        $newUser->rolid = 535751; //Cliente
        $userid = $newUser->insertUser();

        $rv = new stdclass();
        $rv->message = "El usuario ha sido guardado exitosamente.";
        return $response->withJson($rv, 200);
    }

    public function update($request, $response, $args)
    {
        $newData = $request->getParsedBody();
        $userToUpdate = new User();
        $userToUpdate->userid = $newData['userid'];
        $userToUpdate->email = $newData['email'];
        $userToUpdate->password = crypt($newData['password'], "1af324D");
        $userToUpdate->photo = $newData['photo'];
        $userToUpdate->rol = $newData['rol'];
        $rv = new stdclass();
        if ($userToUpdate->updateUser()) {
            $rv->message = "El usuario ha sido actualizado con exitosamente.";
            $newResponse = $response->withJson($rv, 200);
        } else {
            $rv->message = "Hubo un error y no se ha podido actualizar. Comuniquese con el administrador de su sistema.";
            $newResponse = $response->withJson($rv, 404);
        }
        return $newResponse;
    }

    public function delete($request, $response, $args)
    {
        $userToDelete = $request->getParsedBody();
        $userid = $userToDelete['userid'];
        $user = new User();
        $user->userid = $userid;
        $rv = new stdclass();
        if ($user->deleteUser() > 0) {
            $rv->message = "Usuario eliminado exitosamente.";
            $response = $response->withJson($rv, 200);
        } else {
            $rv->message = "Usuario no encontrado.";
            $response = $response->withJson($rv, 404);
        }
        return $response;
    }



    public function validateUser($request, $response, $args)
    {
        try {
            $rv = new stdclass();
            $rv->invalid = array();
            $userData = $request->getParsedBody();
            $password = crypt($userData['password'], "1af324D");
            $email = $userData['email'];
            $user = User::getUserDataByEmailAndPassword($email, $password);
            if ($user != false) {
                
                //get permissions
                $rv->permissions = User::getPermissionsByUserRolId($user->rolid);
                //set return value properties
                $rv->username = $user->username;
                $rv->email = $user->email;
                $jwt = AuthJWT::getToken($user);
                $rv->jwt = $jwt;
                $rv->message = 'Usuario encontrado';
                $response = $response->withJson($rv, 200);
            } else {
                $rv->message = "Los datos no son correctos";
                array_push($rv->invalid,"Verifique los datos ingresados");
                $response = $response->withJson($rv, 404);
            }
            return $response;
        } catch (Exception $ex) {
            $rv->message = "Error desconocido. Comuniquese con el administrador de su sistema.";
            array_push($rv->invalid,"Error");
            $response = $response->withJson($rv, 404);
            return $response;

        }

    }

    function validateRegistrationData($request, $response, $args)
    {
        $rv = new stdclass();
        $rv->invalid = array();
        $userData = $request->getParsedBody();
        $errorMsgs = $this->userIsValid($userData);
        if (count($errorMsgs) > 0) {

            $rv->message = "Los datos no tiene el formato correcto";
            $rv->invalid = $errorMsgs;
            $response = $response->withJson($rv, 404);

        } else {

            if (User::userAlreadyExist($userData['email'])) {
                $rv->message = "El usuario ingresado ya existe";
                array_push($rv->invalid,"Ya se ha registrado un usuario con ese email");
                $response = $response->withJson($rv, 404);
            } else {
            
                $response = $this->insert($request, $response, $args);

                $user = new User();
                $user->username = $userData['username'];
                $user->email = $userData['email'];
                $user->password = $userData['password'];
                $user->rolid = 535751; //Cliente

                $jwt = AuthJWT::getToken($user);
                $rv->jwt = $jwt;
                $rv->message = "El usuario ha sido creado exitosamente.";
                $response = $response->withJson($rv, 200);
            }
        }
        return $response;
    }

    function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    function userIsValid($user)
    {
        $messages = array();
        //email validations
        if (isset($user['email'])) {
            if (strlen($user['email']) != 0) {
                if (strlen($user['email']) > 50) {
                    array_push($messages, "El e-mail es demasiado largo para ser válido");
                }
                if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                    array_push($messages, "El formato del e-mail es incorrecto");
                }
            } else {
                array_push($messages, "Ingresa tu correo electrónico");
            }
        } else {
            array_push($messages, "Ingresa tu correo electrónico");
        }
        /*****************************************************/
        //username validations
        if (isset($user['username'])) {
            if (strlen($user['username']) != 0) {
                if (strlen($user['username']) > 50) {
                    array_push($messages, "El nombre de usuario es demasiado largo para ser válido");
                }
            } else {
                array_push($messages, "Ingresa tu nombre de usuario");
            }
        } else {
            array_push($messages, "Ingresa tu nombre de usuario");
        }
        /*****************************************************/
        //password validations
        if (isset($user['password'])) {
            if (strlen($user['password']) != 0) {
                if (strlen($user['password']) > 50) {
                    array_push($messages, "La contraseña es demasiado larga para ser válida");
                }
            } else {
                array_push($messages, "Ingresa la contraseña");
            }
        } else {
            array_push($messages, "Ingresa la contraseña");
        }
        /*****************************************************/
        return $messages;
    }
}