<?php
include ('../libs/Connexion.php');
include ('../config.php');
include ('../libs/jwt_utils.php');
include('../libs/server_utils.php');



$linkpdo = Connexion::getPDO(DB_NAME,DB_HOST,DB_USER,DB_PASS);
$EXP_TIME = 3600;

$http_method = $_SERVER['REQUEST_METHOD'];
if ($http_method != "POST") {
    deliver_response("501","méthode non supportée, utilisez POST",null);
    exit;
 }

function isValidUser ($login,$pass) {
    global $linkpdo;

    $requete = 'SELECT password_hash from auteur where login = ? ';
    
    $req = $linkpdo->prepare($requete);
    $req->bindValue(1,$login);
    
    try {
        $req->execute();
    }
    catch (Exception $e) {
        $e->getMessage();
    }
    
    if (!$data = $req->fetch()) { 
        return false;
    }
    
    if (password_verify($pass,$data[0])) {
        return true;
    }
    return false;
}



$data = (array) json_decode(file_get_contents('php://input'),TRUE);

if (!isset($data['username']) || !isset($data['password'])) {
    deliver_response(401,"Erreur lors de l'authentification",null);
    exit;
}

if (isValidUser($data['username'],$data ['password'])) {

    $username =  $data ['username'];

    $headers = array('alg'=>'HS256','typ'=>'JWT');
    $payload = array('username'=>$username, 'exp'=>(time()+$EXP_TIME));

    $jwt = generate_jwt($headers,$payload,JWT_SECRET);
    $reponse = ["token"=>$jwt];

    deliver_response(200,"Succes de l'authentification",$reponse);
    
}
else {
    deliver_response(401,"Identifiant/Mot de passe incorrect",null);
}



?>
   
