<?php
$host = "127.0.0.1";
$usuario = "root";
$senha = "";
$banco = "carros_app";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conex�o com o banco de dados: " . $conn->connect_error);
}
?>
