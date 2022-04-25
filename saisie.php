<?php
require "config/config.php";
require "inc/utils.php";
require "inc/vendor/autoload.php";

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

//récupération du matériel
$response = $itemHandler->getItem(ucfirst($_GET["type"]), $_GET["id"]);
$item = json_decode($response['body']);

//récupération de la liste des users
$response = $itemHandler->getAllItems("user");
$users = json_decode($response['body']);

//Récupération des lieux
$response = $itemHandler->getAllItems("location");
$lieux = json_decode($response['body']);


//récupération de la liste des statuts
$response = $itemHandler->getAllItems("State");
$statuts = json_decode($response['body']);

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GLPI - Inventaire physique</title>

        
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="assets/select2-4.0.13/dist/css/select2.min.css" rel="stylesheet">

    </head>

    <body>
        <div class="container">
            <div class="card" style="margin-top:30px;margin-left:auto;margin-right:auto;">
                <div class="card-body">
                    <div class="text-center">
                        <img src="assets/pics/logo_glpi.png" height="30%" width="30%">
                    </div>
                    <h4 class="card-title text-info text-center"><?php echo $item->name; ?> <a href="<?php echo $_GET["url"]; ?>" target="new" style="text-decoration:none;" class="text-info"><i class="fa fa-link"></i></a></h4>
                   
                        <div class="mb-3">
                            <label for="serial" class="form-label">N° de série :</label>
                            <input type="text" class="form-control" id="serial" value="<?php echo $item->serial; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">Utilisateur attribué :</label>
                            <select class="form-select selectpicker" id="user">
                                <?php
                                    foreach ($users as $user)
                                    {
                                        if($user->firstname<>"")
                                        {
                                            echo "<option value='".$user->id."'";
                                            if($item->users_id==$user->id)
                                            {
                                                echo " selected";
                                            }
                                            echo ">".$user->realname." ".$user->firstname."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="lieu" class="form-label">Lieu :</label>
                            <select class="form-select selectpicker" id="lieu">
                                <?php
                                    foreach ($lieux as $lieu)
                                    {
                                        echo "<option value='".$lieu->id."'";
                                        if($item->locations_id==$lieu->id)
                                        {
                                            echo " selected";
                                        }
                                        echo ">".$lieu->name."</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut
                                -- :</label>
                            <select class="form-select selectpicker" id="statut">
                                <?php
                                    foreach ($statuts as $statut)
                                    {
                                        $affiche=0;
                                        switch($_GET["type"])
                                        {
                                            case "computer":
                                                if($statut->is_visible_computer==1)
                                                {
                                                    $affiche=1;
                                                }
                                                break;
                                            case "monitor":
                                                if($statut->is_visible_monitor==1)
                                                {
                                                    $affiche=1;
                                                }
                                                break;
                                            case "networkequipment":
                                                if($statut->is_visible_networkequipment==1)
                                                {
                                                    $affiche=1;
                                                }
                                                break;
                                            case "peripheral":
                                                if($statut->is_visible_peripheral==1)
                                                {
                                                    $affiche=1;
                                                }
                                                break;
                                            case "phone":
                                                if($statut->is_visible_phone==1)
                                                {
                                                    $affiche=1;
                                                }
                                                break;
                                            case "printer":
                                                if($statut->is_visible_printer==1)
                                                {
                                                    $affiche=1;
                                                }
                                                break;
                                             case "networkequipment":
                                                if($statut->is_visible_networkequipment==1)
                                                {
                                                    $affiche=1;
                                                }
                                                break;             
                                        }
                                        if($affiche==1)
                                        {
                                            echo "<option value='".$statut->id."'";
                                            if($item->states_id==$statut->id)
                                            {
                                                echo " selected";
                                            }
                                            echo ">".$statut->completename."</option>";
                                        }
                                    }
                                    
                                ?>
                            </select>
                        </div>
                        <br>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="valider()">Valider</button>
                        </div>
                </div>
            </div>
        </div>


        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/select2-4.0.13/dist/js/select2.full.min.js"></script>
        <script>

            $('#user').select2({
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) {
                            return 1;
                        }
                        if (a.text < b.text) {
                            return -1;
                        }
                        return 0;
                    })
                }
            });
            $('#lieu').select2();
            $('#statut').select2({
                sorter: function(data) {
                    return data.sort(function (a, b) {
                        if (a.text > b.text) {
                            return 1;
                        }
                        if (a.text < b.text) {
                            return -1;
                        }
                        return 0;
                    })
                }
            });

            function valider() {
                
               $.ajax({
                    url: "ajax/maj_item.php",
                    type: "POST",
                    data: {
                        id: <?php echo $_GET["id"]; ?>,
                        users_id: $("#user").val(),
                        locations_id: $("#lieu").val(),
                        type:"<?php echo $_GET["type"]; ?>",
                        states_id: $("#statut").val(),
                    },
                    success: function(response) {
                        var result = $.trim(response);
//alert(result);
                        document.location.href="index.php";
                        
                    }
                });
               //$response = $itemHandler->updateItems("location");
               
           }
        </script>
    </body>
</html>