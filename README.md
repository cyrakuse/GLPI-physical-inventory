# GLPI_physical_inventory
Inventaire physique via lecture de QR Code sur smartphone.

Cet outil utilise l'API REST de GLPI pour mettre à jour les équipements présents dans GLPI.
Pour cela, vous devez utiliser le plugin Barcode pour générer des QR Code pour vos équipements. Les QR Codes doivent contenir à minima les informations suivantes lors de la génération : ID, Nom, Nom, Web page of the device, Web page of the item

# Installation
Il suffit de déposer les fichiers sur un serveur web accessible depuis votre smartphone. De plus, cet outil devra pouvoir avoir accès à votre instance de GLPI.
Ensuite, il faut modifier le fichier config/config.php afin de renseigner les information de votre instance GLPI

# Avertissement
Avant d'utiliser cet outil en production, vous devez impérativement effectuer des tests sur un environnement de pré-production afin de valider son fonctionnement avec votre configuration de GLPI.
Je ne pourrais pas être tenu pour responsable des conséquences de l'utilisation de cet outil.
