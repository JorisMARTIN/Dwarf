<?php

class DAO {
  protected PDO $db;

  function __construct() {
    $this->db = new PDO('pgsql:host=hostname;port=5432;dbname=dwarfBDD', 'dwarf', 'dwarfteamjt');
    if (!$this->db) {
      throw "Erreur lors de l'ouverture de la base de données";
    }
  }
}

?>

