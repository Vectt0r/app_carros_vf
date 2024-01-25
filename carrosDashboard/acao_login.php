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
            //linha que extrai o hash da senha
            //$hashSenha = $row["senha"];

            //condição de verificação do hash
            //if (password_verify($senha, $hashSenha)) {
            if ($senha == $row["senha"]) {
                $_SESSION["nome"] = $nome;
                header("Location: dashboard.php");
                exit();
            }
        }
    }
}
// Se chegou aqui a autenticação falhou
$_SESSION['erroLogin'] = true;
header("Location: index.php");
exit();
?>
