<?php
session_start();
//Inicio de Sessão
if (!isset($_SESSION["nome"])) {
    header("Location: index.php");
    exit();
}

$nome = $_SESSION["nome"];

//Obter dados vindos da API carros
function obterDadosDaAPI() {
    $apiUrl = 'http://localhost:3000/api/getCarrosControle';
    $response = file_get_contents($apiUrl);

    if ($response === FALSE) {
        return false;
    }

    $dadosDaAPI = json_decode($response, true);

    return $dadosDaAPI;
}

//Dados da api armazenados em dados reais
$dadosReais = obterDadosDaAPI();

if ($dadosReais === false) {
    die('Erro ao obter dados da API');
}

//Paginação
$resultadosPorPagina = 20;
$totalRegistros = count($dadosReais);
$totalPaginas = ceil($totalRegistros / $resultadosPorPagina);

$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicial = ($paginaAtual - 1) * $resultadosPorPagina;
$dadosPaginaAtual = array_slice($dadosReais, $indiceInicial, $resultadosPorPagina);

$termoBusca = isset($_GET['busca']) ? $_GET['busca'] : '';
$dadosFiltrados = [];

//Buscar
if (!empty($termoBusca)) {
    foreach ($dadosReais as $dados) {
        // Verifica se o termo de busca está presente no 'nome_funcionario'
        if (isset($dados['nome_funcionario']) && $dados['nome_funcionario'] !== null && stripos($dados['nome_funcionario'], $termoBusca) !== false) {
            $dadosFiltrados[] = $dados;
        }

        // Verifica se o termo de busca está presente na 'placa'
        if (isset($dados['placa']) && $dados['placa'] !== null && stripos($dados['placa'], $termoBusca) !== false) {
            $dadosFiltrados[] = $dados;
        }

        // Verifica se o termo de busca está presente na 'cidade'
        if (isset($dados['cidade']) && $dados['cidade'] !== null && stripos($dados['cidade'], $termoBusca) !== false) {
            $dadosFiltrados[] = $dados;
        }

        // Verifica se o termo de busca está presente na 'data'
        if (isset($dados['data']) && $dados['data'] !== null && stripos($dados['data'], $termoBusca) !== false) {
            $dadosFiltrados[] = $dados;
        }

        // Verifica se o termo de busca está presente na 'localidade'
        if (isset($dados['localidade']) && $dados['localidade'] !== null && stripos($dados['localidade'], $termoBusca) !== false) {
            $dadosFiltrados[] = $dados;
        }
    }
} else {
    $dadosFiltrados = $dadosReais;
}

$dadosPaginaAtual = array_slice($dadosFiltrados, $indiceInicial, $resultadosPorPagina);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Controle de Veiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media screen {
            body {
                padding-top: 100px;
                background-size: cover;
            }
        }

        .custom-table {
            border: 1px solid #ddd;
            margin: auto;
        }

        .custom-table th,
        .custom-table td {
            border: 1px solid #ddd;
            padding: 5px;
            margin: 10px;
            text-align: center;
        }

        .container {
            max-width: 1800px;
        }

        @media print {
            body {
                overflow: hidden;
            }

            .table-scrollable {
                overflow: visible !important;
            }

            .pagination,
            .form-inline {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #146dc5!important">
    <a class="navbar-brand" href="#">
        <img src="imagens/logo_netcomet.png" alt="NetComet Logo" style="height: 50px;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav" >
        <ul class="navbar-nav ml-auto" style="padding-right: 110px;">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="material-icons">home</i> <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="usuarios.php">
                    <i class="material-icons">person</i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="veiculos.php">
                    <i class="material-icons">directions_car</i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="">
                    <i class="material-icons">map</i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                <!--Melhoria para o Futuro-->
                    <i class="material-icons">exit_to_app</i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <form class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="busca" class="sr-only">Buscar</label>
            <input type="text" class="form-control" id="busca" name="busca" placeholder="Digite para buscar">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
        <form class="form-inline mb-3 ml-auto">
            <button type="button" class="btn btn-primary ml-2" id="btnAtualizarTabela">Atualizar Tabela</button>
        <!--<button type="button" class="btn btn-secondary ml-2 btn-imprimir-tabela btn-imprimir">Imprimir Tabela</button> FUTURO-->
        </form>
    </form>

    <div style="height: 680px; overflow-y: auto;">
        <table class="table table-striped table-hover custom-table">
            <thead>
            <tr>
                <th style="white-space: nowrap;">ID</th>
                <th style="white-space: nowrap;">Funcionário</th>
                <th style="white-space: nowrap;">Placa</th>
                <th style="white-space: nowrap;">KM Inicial</th>
                <th style="white-space: nowrap;">KM Final</th>
                <th style="white-space: nowrap;">Saída</th>
                <th style="white-space: nowrap;">Chegada</th>
                <th style="white-space: nowrap;">Data</th>
                <th style="white-space: nowrap;">Cidade</th>
                <th style="white-space: nowrap;">Localidade</th>
                <th style="white-space: nowrap;">Cidade 2</th>
                <th style="white-space: nowrap;">Localidade 2</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dadosPaginaAtual as $dados) : ?>
                <tr>
                    <td style="white-space: nowrap;"><?php echo $dados['id']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['nome_funcionario']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['placa']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['km_inicial']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['km_final']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['hora_saida']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['hora_chegada']; ?></td>
                    <td style="white-space: nowrap;">
                        <?php
                        if (isset($dados['data'])) {
                            $data = $dados['data'] ? new DateTime($dados['data']) : null;
                            $dataFormatada = $data ? $data->format("d/m/Y") : 'Data indefinida';
                            echo $dataFormatada;
                        }
                        ?>
                    </td>
                    <td><?php echo $dados['cidade']; ?></td>
                    <td><?php echo $dados['localidade']; ?></td>
                    <td><?php echo $dados['cidade_02']; ?></td>
                    <td><?php echo $dados['localidade_02']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div>
        <ul class="pagination justify-content-center" style="margin-top: 7px;">
            <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $i . '&busca=' . urlencode($termoBusca); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#btnAtualizarTabela').click(function () {
                location.reload();
            });
            
            //Desabilitado
            $('.btn-imprimir-tabela').click(function () {
                window.print();
            });
        });
    </script>
</body>
</html>
