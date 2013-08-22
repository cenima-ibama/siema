<?php
/*
    Database Connections
*/

// Return database connection
function pgConnection() {
	$conn = new PDO ("pgsql:host=10.1.8.65;dbname=emergencias;port=5432","postgres","shgtl275", array(PDO::ATTR_PERSISTENT => true));
    return $conn;
}

?>
