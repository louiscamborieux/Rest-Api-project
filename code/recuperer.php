<?php 
 require "../config.php";
 require "../libs/server_utils.php";
 require "../libs/Connexion.php";
 include("../libs/jwt_utils.php");

 function checkbearer() {
   $bearer_token = '';
   $bearer_token = get_bearer_token();

   
   try {
   if (!is_jwt_valid($bearer_token,JWT_SECRET)){
       return null;
   }
   }
   catch (Exception $e) {
       return null;
   }
   return $bearer_token;
}

const FIELDS  = ["auteur","postid","date_publication","contenu", "likes","dislikes","date_modification"];

$bearer = checkbearer();

 $http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method) {
case "GET" : {
         $precisid = null;
   if (isset($_GET['id'])) {
      $precisid = " AND post.id = ?";
   }

   if ($bearer) {
      $requete = "SELECT login as auteur, id_post as postid, date_publication,date_modification , contenu,
      sum(case when reac = '1' then 1 else 0 end) AS likes,
      sum(case when reac = '0' then 1 else 0 end) AS dislikes
      FROM reaction, post, auteur
      where post.id = reaction.id_post
      and auteur.id_auteur = post.id_auteur";
      if (isset($precisid)) {
         $requete = $requete.$precisid;
      }
      $requete = $requete."
      GROUP BY id_post
      ORDER BY date_publication";
      $reponse = array("connected" => true);
   }
   else {
      $requete = "SELECT login as auteur, contenu, date_publication
      FROM  post, auteur
      where auteur.id_auteur = post.id_auteur";
      if (isset($precisid)) {
         $requete = $requete.$precisid;
      }
      $reponse = array("connected" => false);
   }

   try {
      $linkpdo = Connexion::getPDO(DB_NAME,DB_HOST,DB_USER,DB_PASS);

      $st = $linkpdo->prepare($requete);
      if ($precisid) {
         $st->bindParam(1,$_GET['id']);
      }
      $st->execute();
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur DB",null);
      exit;
   }


 
 if (!($data = $st->fetchALL(PDO::FETCH_ASSOC)) && $precisid != null) {
   deliver_response(404,'ressource introuvable',$reponse);
 }
 else {
   $reponse["messages"] = $data;
   deliver_response(200,'success',$reponse);
 }
 break;
}
case "POST" : {
  if (!$bearer) {
   deliver_response(401,"Erreur d'authentification",null);
   exit;
  }

   $partiesToken = explode('.',$bearer);
   $payload = json_decode(base64_decode($partiesToken[1]),true);
   $username = $payload['username'];
  

   $postedData = file_get_contents('php://input');
   $jsonData = json_decode($postedData,TRUE);

   if ($jsonData == null ) {
      deliver_response(400,"Ressource mal formattée ou vide",null);
      exit;
   }

   foreach(FIELDS as $field) {
      if (!array_key_exists($field,$jsonData)) {
         deliver_response(400,"Ressource incomplète",null);
         exit;
      }
   }
   
   try {
      $linkpdo = Connexion::getPDO(DB_NAME,DB_HOST,DB_USER,DB_PASS);
      $stGetID = $linkpdo->prepare ("Select id_auteur from auteur where login = ?");
      $stGetID->bindParam(1,$username);
      $stGetID -> execute();
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur DB",null);
      exit;
   }

   if (!$data =$stGetID->fetch()) {
      deliver_response(500,"Utilisateur introuvable",null);
      exit;
   }
   $idAuteur = $data['id_auteur'];

   $stInsert = $linkpdo->prepare("Insert Into Post VALUES ( default, ?, ?, ?,default)");
   
   try {
      $stInsert->execute(array($idAuteur ,$jsonData["contenu"],date('Y-m-d H:i:s', time())));
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur BD",null);
      exit;
   }

   $stLastPost  = $linkpdo->prepare("SELECT login as auteur, id_post as postid, date_publication,date_modification , contenu,
   sum(case when reac = '1' then 1 else 0 end) AS likes,
   sum(case when reac = '0' then 1 else 0 end) AS dislikes
   FROM reaction, post, auteur
   where post.id = reaction.id_post
   and auteur.id_auteur = post.id_auteur
   and id = ?
   GROUP BY id_post");

   $dernierId = $linkpdo->lastInsertID();

   $stAddReact = $linkpdo->prepare ("Insert into reaction values (?, ? , null)");

   try {
      $stAddReact->execute(array($idAuteur ,$dernierId));
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur BD",null);
      exit;
   }

   try {
      $stLastPost->execute(array($dernierId));
      $stLastPost->execute();
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur DB",null);
      exit;
   }

   if (!$data = $stLastPost->fetch(PDO::FETCH_ASSOC)) {
      deliver_response(500,"Ressource non envoyée",null);
      exit;
   }
   deliver_response(201,"Succes de l'ajout",$data);


   
 break;
}

case "DELETE" : {
   if (!isset($_GET['id'])) {
      deliver_response(400,"identifiant non precisé",null);
      exit;
   }

   if (!$bearer) {
      deliver_response(401,"Erreur d'authentification",null);
      exit;
     }

   //Verification de la présence du post
   try {
      $linkpdo = Connexion::getPDO(DB_NAME,DB_HOST,DB_USER,DB_PASS);
      $stCheckPost = $linkpdo->prepare("SELECT id
      FROM post
      WHERE id = ?");
      $stCheckPost->bindParam(1,$_GET["id"]);
      $stCheckPost->execute();
   }
   catch (Exception $e){
       deliver_response(500,"erreur DB",null);
       exit;
   }

   if (!$post = $stCheckPost->fetch()) {
      deliver_response(404,"Errreur, post introuvable",null);
      exit;
   }

   //Verification de l'auteur
   $partiesToken = explode('.',$bearer);
   $payload = json_decode(base64_decode($partiesToken[1]),true);
   $username = $payload['username'];

   try {
      $stIsAuteur = $linkpdo->prepare("Select login from auteur, post where login = ?
      and post.id = ?
      and auteur.id_auteur = post.id_auteur");
      $stIsAuteur->execute(array($username,$_GET['id']));
   }
   catch (Exception $e){
      exit;
   }

   if (!$data = $stIsAuteur->fetch()) {
      //Si non auteur verification du rôle

      try {

         $stcheckmod = $linkpdo->prepare("Select id_auteur, role from auteur where login = ?");
         $stcheckmod->bindParam(1,$username);
         $stcheckmod->execute();
      }
      catch (Exception $e) {
         deliver_response(500,"Erreur DB",null);
         exit;
      }
      $data = $stcheckmod->fetch();
      //Verification du role
      if (!$data || $data['role'] != 'moderator') {
         deliver_response(403,"Vous n'avez pas la permission de supprimer cette ressource",null);
         exit;
      }
   }

   $stDeleteReacts = $linkpdo->prepare("DELETE from reaction where id_post = ?");
   try {
      $stDeleteReacts->execute(array($_GET['id']));
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur DB",null);
      exit;
   }

   $stDeletePost = $linkpdo->prepare("DELETE from post where id = ?");
   try {
      $stDeletePost->execute(array($_GET['id']));
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur DB",null);
      exit;
   }

   deliver_response(204,"Ressource supprimée",null);

   break;
}

case "PUT" : {
   if (!isset($_GET['id'])) {
      deliver_response(400,"identifiant non precisé",null);
      exit;
   }

   if (!$bearer) {
      deliver_response(401,"Erreur d'authentification",null);
      exit;
     }

   try {
      $linkpdo = Connexion::getPDO(DB_NAME,DB_HOST,DB_USER,DB_PASS);
      $stCheckPost = $linkpdo->prepare("SELECT id
      FROM post
      WHERE id = ?");
      $stCheckPost->bindParam(1,$_GET["id"]);
      $stCheckPost->execute();
   }
   catch (Exception $e){
       deliver_response(500,"erreur DB",null);
       exit;
   }

   if (!$post = $stCheckPost->fetch()) {
      deliver_response(404,"Erreur, post introuvable",null);
      exit;
   }

   //Verification de l'auteur
   $partiesToken = explode('.',$bearer);
   $payload = json_decode(base64_decode($partiesToken[1]),true);
   $username = $payload['username'];

   try {
      $stIsAuteur = $linkpdo->prepare("Select login from auteur, post where login = ?
      and post.id = ?
      and auteur.id_auteur = post.id_auteur");
      $stIsAuteur->execute(array($username,$_GET['id']));
   }
   catch (Exception $e){
      exit;
   }

   if (!$data = $stIsAuteur->fetch()) {
      deliver_response(403,"Vous n'êtes pas l'auteur de cette ressource",null);
      break;
   }

   $postedData = file_get_contents('php://input');
   $jsonData = json_decode($postedData,TRUE);

   if ($jsonData == null ) {
      deliver_response(400,"Ressource mal formatté",null);
      exit; 
   }

   foreach(FIELDS as $field) {
      if (!array_key_exists($field,$jsonData)) {
         deliver_response(400,"Ressource incomplète",null);
         exit;
      }
   }

   $stUpdate = $linkpdo->prepare("Update Post set Contenu = ?, date_modification = ? where id = ?");

   try {
      $stUpdate->execute(array($jsonData["contenu"],date('Y-m-d h:i:s',time()),$_GET["id"]));
   }
   catch (Exception $e) {
      deliver_response(500,"Erreur DB",null);
      exit;
   }

   $stModified  = $linkpdo->prepare("SELECT login as auteur, id_post as postid, date_publication,date_modification , contenu,
   sum(case when reac = '1' then 1 else 0 end) AS likes,
   sum(case when reac = '0' then 1 else 0 end) AS dislikes
   FROM reaction, post, auteur
   where post.id = reaction.id_post
   and auteur.id_auteur = post.id_auteur
   and id = ?
   GROUP BY id_post");
   try {
      $stModified->execute(array($_GET["id"]));
   }
   catch (Exception $e ) {
      deliver_response(500,"Erreur DB",null);
   }

   $postModifie = $stModified->fetch(PDO::FETCH_ASSOC);
   deliver_response(200,"Ressource modifiée",$postModifie);
   break;



   

}

default :
    deliver_response(501,"méthode non supportée",null);
    exit;
 }

 





?>