<?php

class DAO {
  protected $db;

  function __construct() {
    $this->db = new PDO('pgsql:host=localhost;port=5432;dbname=dwarfBDD', 'dwarf', 'dwarfteamjt');
    if (!$this->db) {
      throw new Exception("Erreur lors de l'ouverture de la base de données");
    }
  }
}

?>

