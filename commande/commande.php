<?php

 
// commande.php
require_once 'config.php';

class Commande {
    private $db;

    function __construct() {
        $this->db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
    }

    // Récupérer toutes les commandes
    function getAllCommandes() {
        $stmt = $this->db->prepare("SELECT * FROM commandes");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

         // Convertir le résultat en JSON
    $json = json_encode($result);

    //je sepcifie le type de contenu de la réponse
    header('Content-Type: application/json');
        
    // Retourner le résultat au format JSON
    echo $json;
    }

    // Récupérer une commande par son id
    function getCommandeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM commandes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    // Ajouter une commande
    function add_commande($client_id, $produits) {
        
        // Insertion de la commande
        $stmt = $this->db->prepare("INSERT INTO commandes(client_id) VALUES (:client_id)");
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        $commande_id = $this->db->lastInsertId();

        
        // Insertion des produits commandés dans la table commande_produit
        foreach ($produits as $produit) {
            $stmt = $this->db->prepare("INSERT INTO commande_produit(commande_id, nom, quantite,prix) VALUES (:commande_id, :nom, :quantite,:prix)");
            $stmt->bindParam(':commande_id', $commande_id);
            $stmt->bindParam(':nom', $produit['nom']);
            $stmt->bindParam(':quantite', $produit['quantite']);
            $stmt->bindParam(':prix', $produit['prix']);
            $stmt->execute();
        }
        return true;
    }


 
}

 
