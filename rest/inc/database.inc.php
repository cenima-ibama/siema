<?php
/*
    Database Connections
*/

// Return database connection
function pgConnection() {
	$conn = new PDO ("pgsql:host=10.1.8.45;dbname=emergencias_homolog;port=5432","emergencias","3m3rg3nc14s", array(PDO::ATTR_PERSISTENT => true));
    return $conn;
}

?>
