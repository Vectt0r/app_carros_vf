<?php
session_start();
require_once('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["nome"]) && isset($_POST["senha"])) {
        $nome = $_POST["nome"];
        $senha = $_POST["senha"];

        $sql = "SELECT * FROM carros_usuarios WHERE nome = '$nome'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verificar a senha hasheada usando password_verify
            if (password_verify($senha, $row["senha"])) {
                $_SESSION["nome"] = $nome;
                header("Location: dashboard.php");
                exit();
            }
        }
    }
}

$_SESSION['erroLogin'] = true;
header("Location: index.php");
exit();
?>
<!--session_start();-->
<!--require_once('conexao.php');-->
<!---->
<!--if ($_SERVER["REQUEST_METHOD"] == "POST") {-->
<!--if (isset($_POST["nome"]) && isset($_POST["senha"])) {-->
<!--$nome = $_POST["nome"];-->
<!--$senha = $_POST["senha"];-->
<!---->
<!--$sql = "SELECT * FROM carros_usuarios WHERE nome = '$nome'";-->
<!--$result = $conn->query($sql);-->
<!---->
<!--if ($result->num_rows > 0) {-->
<!--$row = $result->fetch_assoc();-->
<!--// linha que extrai o hash da senha-->
<!--$hashSenha = $row["senha"];-->
<!---->
<!--// Verifica se a senha fornecida corresponde ao hash armazenado no banco-->
<!--if (password_verify($senha, $hashSenha)) {-->
<!--$_SESSION["nome"] = $nome;-->
<!--header("Location: dashboard.php");-->
<!--exit();-->
<!--} else {-->
<!--// Se a senha não corresponde, a autenticação falhou-->
<!--$_SESSION['erroLogin'] = true;-->
<!--header("Location: index.php");-->
<!--exit();-->
<!--}-->
<!--}-->
<!--}-->
<!--}-->
<!---->
<!--// Se chegou aqui, a autenticação falhou-->
<!--$_SESSION['erroLogin'] = true;-->
<!--header("Location: index.php");-->
<!--exit();-->
