<?php
//require "config/config.php";
include "inc/utils.php";

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GLPI - Inventaire physique</title>

        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/font-awesome/css/all.min.css" rel="stylesheet">

    </head>

    <body>
        <div class="container">
            <div class="card" style="margin-top:30px;margin-left:auto;margin-right:auto;">
                <div class="card-body">
                    <div class="text-center">
                        <img src="assets/pics/logo_glpi.png" height="30%" width="30%">
                    </div>
                    <h4 class="card-title text-info text-center">Scan du QR Code du matériel</h4>

                    <div class="col-md-12 text-center" style="margin-top:50px"> 
                        <div id="qr-reader"></div>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/font-awesome/js/all.min.js"></script>
        <script src="assets/html5-qrcode-master/minified/html5-qrcode.min.js"></script>
        


        <script>

            const html5QrCode = new Html5Qrcode("qr-reader");
            var cameraId;
            





            function docReady(fn) {
                // see if DOM is already available
                if (document.readyState === "complete"
                    || document.readyState === "interactive") {
                    // call on next available tick
                    setTimeout(fn, 1);
                } else {
                    document.addEventListener("DOMContentLoaded", fn);
                }
            }

            docReady(function () {
                var resultContainer = document.getElementById('qr-reader-results');
                var lastResult, countResults = 0;
                function onScanSuccess(decodedText, decodedResult) {
                    if (decodedText !== lastResult) {
                        ++countResults;
                        lastResult = decodedText;
                        // Handle on success condition with the decoded message.
                        
                        var result=lastResult.split("\n");

                        var id;
                        var nom;
                        var url;
                        var type;
                        result.forEach(function(valeur) {
                            //alert(valeur);
                            if(valeur.startsWith("ID = ")) {
                                var  temp=valeur.split(" = ");
                                id=temp[1];
                            }
                            if(valeur.startsWith("URL = ")) {
                                var  temp=valeur.split(" = ");
                                url=temp[1];
                                if(~url.indexOf("computer.form.php"))
                                {
                                    type="computer";
                                }
                                if(~url.indexOf("monitor.form.php"))
                                {
                                    type="monitor";
                                }
                                if(~url.indexOf("networkequipment.form.php"))
                                {
                                    type="networkequipment";
                                }
                                if(~url.indexOf("peripheral.form.php"))
                                {
                                    type="peripheral";
                                }
                                if(~url.indexOf("printer.form.php"))
                                {
                                    type="printer";
                                }
                            }
                        });

                        document.location.href="saisie.php?id="+id+"&nom="+nom+"&type="+type+"&url="+url;
                    }
                }

                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", { fps: 10, qrbox: 250 });
                html5QrcodeScanner.render(onScanSuccess);
            });

        </script>

    </body>
</html>