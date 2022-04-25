<?php
require "../config/config.php";
require "../inc/utils.php";
require "../inc/vendor/autoload.php";

setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');


// Instanciate the API client
$client = new Glpi\Api\Rest\Client(URL_GLPI.'/apirest.php/', new GuzzleHttp\Client());

// Authenticate
try {
   
   $client->setAppToken(GLPI_APPTOKEN);
   $client->initSessionByCredentials(GLPI_USER, GLPI_PASS);

} catch (Exception $e) {
   echo $e->getMessage();
   die();
}
$itemHandler = new \Glpi\Api\Rest\ItemHandler($client);


//Mise à jour du matériel
$datas= array(
    "users_id" => $_POST["users_id"],
    "locations_id" => $_POST["locations_id"],
    "states_id" => $_POST["states_id"]
);

$response = $itemHandler->updateItems($_POST["type"],$_POST["id"],$datas);


//Mise à jour infos de gestion (date d'inventaire physique)

//Récup infocoms
$response = $itemHandler->getSubItems($_POST["type"],$_POST["id"], "Infocom");
$item = json_decode($response['body']);

if(!empty($item))
{
    //si infocoms actives
    $datas= array(
        "inventory_date" => date('Y-m-d H:i:s')
    );
    $response = $itemHandler->updateItems("Infocom",$item[0]->id,$datas);
}
else
{
    //si infocoms pas actives
    $datas= array(
        "items_id" => $_POST["id"],
        "itemtype" => $_POST["type"],
        "entities_id" => 1,
        "inventory_date" => date('Y-m-d H:i:s')
    );
    $response = $itemHandler->addItems("Infocom",$datas);
}
?>