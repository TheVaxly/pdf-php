<?php
$host = "192.168.44.99";
$username = "marten";  // Teie MySQL kasutajanimi
$password = 'sussybaka123';      // Teie MySQL parool
$database = 'joulutervitused';

// Ühenduse loomine
$conn = new mysqli($host, $username, $password, $database);

// Kontrolli ühendust
if ($conn->connect_error) {
    die("Ühendus ebaõnnestus: " . $conn->connect_error);
}
?>
