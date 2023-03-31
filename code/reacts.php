<?php 
 require "../config.php";
 require "../libs/server_utils.php";
 require "../libs/Connexion.php";
 include("../libs/jwt_utils.php");

 const LIKE = 1;
 const DISLIKE = 0;
 const CANCEL = null;

 $http_method = $_SERVER['REQUEST_METHOD'];

$bearer_token = '';
$bearer_token = get_bearer_token();

 try {
    if (!@is_jwt_valid($bearer_token,JWT_SECRET)){
        deliver_response(401,"Erreur d'authentification, Veuillez vous authentifier pour acceder à cette ressource",null);
        exit;
        }
    }
catch (Exception $e) {
    deliver_response(401,"Erreur d'authentification, Veuillez vous authentifier pour acceder à cette ressource",null);
    exit;
    }
$partiesToken = explode('.',$bearer_token);
$payload = json_decode(base64_decode($partiesToken[1]),true);
$username = $payload['username'];
$linkpdo = Connexion::getPDO(DB_NAME,DB_HOST,DB_USER,DB_PASS);
if (!isset($_GET['id'])) {
    deliver_response(400,"id du post non renseigné",null);
    exit;
}

 switch ($http_method) {
    case  "GET" :  {

        $reqmod = "Select id_auteur, role from auteur where login = ?";
        $stcheckmod = $linkpdo->prepare($reqmod);
        $stcheckmod->bindParam(1,$username);
        try {
        $stcheckmod->execute();
        }
        catch (Exception $e) {
            deliver_response(500,"Erreur DB",null);
            exit;
        }
        $data = $stcheckmod->fetch();
        //Verification du role
        if (!$data || $data['role'] != 'moderator') {
            deliver_response(403,"Access refusé",null);
            exit;
        }

        $stdata = $linkpdo->prepare("SELECT id_auteur, id
        FROM post
        WHERE id = ?");

        $stdata->bindParam(1,$_GET["id"]);
        try {
            $stdata->execute();
        }
        catch (Exception $e){
            deliver_response(500,"erreur DB",null);
            exit;
        }

        if (!$post = $stdata->fetch()) {
            deliver_response(404,"Erreur, post introuvable",null);
            exit;
        }
 
        $requetedata = "Select login as utilisateur, role
        from auteur, reaction 
        where id_post = ? and reac = ?
        and auteur.id_auteur = reaction.id_auteur";
        $stdata = $linkpdo->prepare($requetedata);

        $stdata->bindParam(1,$_GET['id']);
    

        if (isset ($_GET["type"]) &&  $_GET["type"] == "dislike") {
            $stdata->bindValue(2,DISLIKE);
        }
        else {
            $stdata->bindValue(2,LIKE);
        }

        try {
            $stdata->execute();
            }
        catch (Exception $e) {
            deliver_response(500,"Erreur DB",null);
            exit;
        }

        deliver_response(200,"Success",$stdata->fetchAll(PDO::FETCH_ASSOC));

        break;
    }
    case "POST" :  {
        $stdata = $linkpdo->prepare("SELECT id_auteur, id
        FROM post
        WHERE id = ?");

        $stdata->bindParam(1,$_GET["id"]);
        try {
            $stdata->execute();
        }
        catch (Exception $e){
            deliver_response(500,"erreur DB",null);
            exit;
        }

        if (!$post = $stdata->fetch()) {
            deliver_response(404,"Erreur, post introuvable",null);
            exit;
        }

        $stgetID =  $linkpdo->prepare("Select id_auteur from auteur where login = ?");
        try {
            $stgetID->execute(array($username));
        }
        catch (Exception $e) {
            deliver_response(500,"Erreur DB",null);
            break;
        }

        if (!$data = $stgetID->fetch()) {
            deliver_response(401,"Erreur authentification",null);
            break;
        }
        $username = $data["id_auteur"];


        //Verification s'il est auteur du post
        try {
            $stIsAuteur = $linkpdo->prepare("Select id_auteur from post where id_auteur = ?
            and post.id = ?");
            $stIsAuteur->execute(array($username,$_GET['id']));
            }
            catch (Exception $e){
            echo $e->getMessage();
            exit;
            }
        
            if ($data = $stIsAuteur->fetch()) {
            deliver_response(403,"Vous ne pouvez pas réagir à un post dont vous êtes l'auteur",null);
            break;
            }

        $stcheckreac = $linkpdo->prepare("SELECT id_post from reaction where id_post =? and id_auteur = ?" );
        $stcheckreac->bindParam(1,$_GET["id"]) ;
        $stcheckreac->bindParam(2,$username) ;
        try {
            $stcheckreac->execute();
        }
        catch (Exception $e){
            deliver_response(500,"erreur DB",null);
            exit;
        }

        if ($data = $stcheckreac->fetch()) {
            $requeteUpsert = "UPDATE reaction set reac = :reac where id_post = :idpost and id_auteur = :idauteur";
        }
        else {
            $requeteUpsert = "INSERT into reaction (reac,id_post,id_auteur) 
            values (:reac , :idpost, :idauteur)";     
        }
        $stUpsert = $linkpdo->prepare($requeteUpsert);
        $stUpsert->bindParam('idpost',$_GET["id"]);
        $stUpsert->bindParam('idauteur',$username,PDO::PARAM_INT);

        $stUpsert->bindValue('reac',LIKE);
        if (isset ($_GET["type"])) {
            if ($_GET["type"] == "dislike") {
                $stUpsert->bindValue('reac',DISLIKE);
            }
            else if  ($_GET["type"] == "unreact") {
                $stUpsert->bindValue('reac',CANCEL,PDO::PARAM_NULL);
            }
        }

        
        try {
            $stUpsert->execute();
        }
        catch (Exception $e){
            deliver_response(500,"erreur DB",null);
            exit;
        }

        $streponse = $linkpdo->prepare("SELECT login as auteur, id_post as postid,date_publication, contenu,
        sum(case when reac = '1' then 1 else 0 end) AS likes,
        sum(case when reac = '0' then 1 else 0 end) AS dislikes
        FROM reaction, post, auteur
        where post.id = reaction.id_post
        and auteur.id_auteur = post.id_auteur
        and post.id = ?
        Group by id_post");

        $streponse->bindParam(1,$_GET["id"]);
        try {
            $streponse->execute();
        }
        catch (Exception $e){
            deliver_response(500,"erreur DB",null);
            exit;
        }


        deliver_response(201,'reaction ajoutée',$streponse->fetch(PDO::FETCH_ASSOC));


        break;
    }
    default :     
    deliver_response("501","méthode non supportée.",null);
    break;
 }









 ?>