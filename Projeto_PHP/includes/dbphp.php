<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "restaurant_pacolla"; 

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
