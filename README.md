# GLPI_physical_inventory
Inventaire physique via lecture de QR Code sur smartphone.

Cet outil utilise l'API REST de GLPI pour mettre à jour les équipements présents dans GLPI.
Pour cela, vous devez utiliser le plugin Barcode pour générer des QR Code pour vos équipements. Les QR Codes doivent contenir à minima les informations suivantes lors de la génération : ID, Nom, Nom, Web page of the device, Web page of the item

# Installation
Il suffit de déposer les fichiers sur un serveur web accessible depuis votre smartphone.
Ensuite, il faut modifier le fichier config/config.php afin de renseigner les information de votre instance GLPI
