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
        if (!$user)
            {
            $rv = new stdclass();
            $rv->message = "Recurso no encontrado";
            $newResponse = $response->withJson($rv, 404);
        }
        else
            {
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

        $file = $request->getUploadedFiles()['photo'];
        $filename = "";
        $folder = "./uploads/";
        if (!empty($file))
            {
            $filename = $this->moveUploadedFile($folder,$file);
        }

        $newUserData = $request->getParsedBody();
        $password = password_hash($newUserData["password"], PASSWORD_BCRYPT);

        $newUser = new User();
        $newUser->email = $newUserData["email"];
        $newUser->password = $password;
        $newUser->photo = $filename;
        $userid = $newUser->insertUser();

        $rv = new stdclass();
        $rv->message = "El usuario ha sido guardado exitosamente.";
        return $response->withJson($rv, 200);
    }

    public function update($request, $response, $args)
    {
        $newData = $request->getParams();
        $userToUpdate = new User();
        $userToUpdate->userid = $newData['userid'];
        $userToUpdate->titulo = $newData['email'];
        $userToUpdate->password = $newData['password'];
        $rv = new stdclass();
        if ($userToUpdate->updateUser()) {
            $rv->message = "El usuario ha sido actualizado con exitosamente.";
            $newResponse = $response->withJson($rv, 200);
        }
        else {
            $rv->message = "Hubo un error y no se ha podido actualizar. Comuniquese con el administrador de su sistema.";
            $newResponse = $response->withJson($rv, 404);
        }
        return $newResponse;
    }

    public function delete($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $id = $ArrayDeParametros['id'];
        $cd = new cd();
        $cd->id = $id;
        $cantidadDeBorrados = $cd->BorrarCd();

        $objDelaRespuesta = new stdclass();
        $objDelaRespuesta->cantidad = $cantidadDeBorrados;
        if ($cantidadDeBorrados > 0) {
            $objDelaRespuesta->resultado = "algo borro!!!";
        }
        else {
            $objDelaRespuesta->resultado = "no Borro nada!!!";
        }
        $newResponse = $response->withJson($objDelaRespuesta, 200);
        return $newResponse;
    }

    function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}