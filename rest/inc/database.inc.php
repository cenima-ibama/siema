<?php
/*
    Database Connections
*/

// Return database connection
function pgConnection($persistence=true) {
	$conn = new PDO ("pgsql:host=10.1.8.45;dbname=emergencias_test;port=5432","development","development", array(PDO::ATTR_PERSISTENT => $persistence));
    return $conn;
}

?>
