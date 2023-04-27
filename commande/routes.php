<?php
// inclure les fichiers nécessaires
require_once 'config.php';
require_once 'Commande.php';

// instancier le contrôleur de commande
$commandeController = new Commande();

// route pour la création d'une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/commandes') {
    $commandeController->createCommande();
}

// route pour la récupération de toutes les commandes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/commandes') {
    $commandeController->getAllCommandes();
}

// route pour la récupération d'une commande par son identifiant
if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('/^\/commandes\/([0-9]+)$/', $_SERVER['REQUEST_URI'], $matches)) {
    $idCommande = $matches[1];
    $commandeController->getCommandeById($idCommande);
}

 

?>
