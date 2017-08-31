<?php
require_once 'user.php';
require_once 'IApiUsable.php';


class userApi extends User implements IApiUsable{

    public function getById($request, $response, $args){

    } 

    public function getAll($request, $response, $args){
        $users=User::getAllUsers();
     	$newresponse = $response->withJson($users, 200);  
    	return $newresponse;
    }

    public function insert($request, $response, $args){

    }

    public function delete($request, $response, $args){

    }
    
    public function update($request, $response, $args){

    }
    
}