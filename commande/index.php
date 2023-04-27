<?php
 
require_once 'config.php';
require_once 'Commande.php';

 
$commandeController = new Commande();

// route pour la création d'une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/commandes') {
$prouits = getJ('produits');  
    $commandeController->add_commande(1,$prouits);
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

 

function getJ($d){
// Récupération du corps de la requête en JSON
$json = file_get_contents('php://input');

// Conversion du JSON en tableau associatif
$data = json_decode($json, true);

// Accès aux données du tableau associatif
return $data[$d];
}

?>
