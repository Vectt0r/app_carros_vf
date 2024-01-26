<?php

// Coletar dados do formulário
$nome = $_POST["nomeNovoUsuario"];
$nome_completo = $_POST["nomeCompletoNovoUsuario"];
//$senha = $_POST["senhaNovoUsuario"];
$senha = password_hash($_POST["senhaNovoUsuario"],PASSWORD_DEFAULT);
$setor = $_POST["setorNovoUsuario"];
$telefone = $_POST["telefoneNovoUsuario"];

// Dados a serem enviados para a API
$dados = [
    'nome' => $nome,
    'nome_completo' => $nome_completo,
    'senha' => $senha,
    'setor' => $setor,
    'telefone' => $telefone
];

// Converte os dados para JSON
$dadosJson = json_encode($dados);

// URL do endpoint da API
$apiEndpoint = 'http://localhost:3000/api/CadastrarNovoUsuario';

// Configura as opcoes para a requisição
$options = [
    'headers' => [
        'Content-Type' => 'application/json',
    ],
];

// Inicializa o cURL
$ch = curl_init($apiEndpoint);

// Configura as opções da requisição cURL
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosJson);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Executa a requisicao cURL e obtém a resposta
$resultado = curl_exec($ch);

// Verifica por erros na requisicao cURL
if (curl_errno($ch)) {
    echo 'Erro na requisição cURL: ' . curl_error($ch);
} else {
    // A resposta da API está armazenada em $resultado
    echo 'Resposta da API: ' . $resultado;
}

// Fecha a sessão cURL
curl_close($ch);

?>
