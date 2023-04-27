<?php
require_once 'config.php';

class Commande_Produit {
    private $db;

    function __construct() {
        $this->db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
    }

    // Récupérer tous les produits d'une commande
    function get_produits_by_commande_id($commande_id) {
        $stmt = $this->db->prepare("SELECT * FROM commande_produit WHERE commande_id = :commande_id");
        $stmt->bindParam(':commande_id', $commande_id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
