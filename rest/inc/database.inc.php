<?php
/*
    Database Connections
*/

// Return database connection
function pgConnection() {
	$conn = new PDO ("pgsql:host=10.1.8.65;dbname=indicar;port=5432","alertaconsulta","sxpalrt", array(PDO::ATTR_PERSISTENT => true));
    return $conn;
}

?>
