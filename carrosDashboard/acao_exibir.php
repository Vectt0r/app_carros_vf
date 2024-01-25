<?php

function obterDadosDaAPI() {
    // URL da rota da API
    $apiUrl = 'http://localhost:3000/api/CadastrarNovoUsuario';

    // Faz uma solicita��o HTTP GET para a rota da API
    $response = file_get_contents($apiUrl);

    // Verifica se a solicita��o foi bem-sucedida
    if ($response === FALSE) {
        return false;
    }

    // Converte a resposta JSON para um array associativo
    $dadosDaAPI = json_decode($response, true);

    return $dadosDaAPI;
}

?>
