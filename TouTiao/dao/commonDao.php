<?php
function createConnection() {
	$url = "localhost:3307";
	$userName = "root";
	$password = "1234";
	$db = "wexun";
	$conn = new mysqli ( $url, $userName, $password, $db );
	return $conn;
}

function closeConnection($conn) {
	if($conn)$conn->close();
}
?>