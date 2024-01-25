<?php
session_start();

if (!isset($_SESSION["nome"])) {
    header("Location: index.php");
    exit();
}

$nome = $_SESSION["nome"];

function obterDadosDaAPI() {
    $apiUrl = 'http://localhost:3000/api/getCarrosControle';
    $response = file_get_contents($apiUrl);

    if ($response === FALSE) {
        return false;
    }

    $dadosDaAPI = json_decode($response, true);

    return $dadosDaAPI;
}

$dadosReais = obterDadosDaAPI();

if ($dadosReais === false) {
    die('Erro ao obter dados da API');
}

$resultadosPorPagina = 20;
$totalRegistros = count($dadosReais);
$totalPaginas = ceil($totalRegistros / $resultadosPorPagina);

$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicial = ($paginaAtual - 1) * $resultadosPorPagina;
$dadosPaginaAtual = array_slice($dadosReais, $indiceInicial, $resultadosPorPagina);

$termoBusca = isset($_GET['busca']) ? $_GET['busca'] : '';
$dadosFiltrados = [];

if (!empty($termoBusca)) {
    foreach ($dadosReais as $dados) {
        if (stripos($dados['nome'], $termoBusca) !== false || stripos($dados['placa'], $termoBusca) !== false) {
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media screen {
            body {
                padding-top: 80px;
                background-size: cover;
            }
        }

        .table-scrollable {
            max-height: 680px;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .custom-container {
            max-width: 120%;
            margin: auto;
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

        .custom-alert {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .custom-alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        @media print {
            .btn-imprimir,
            .custom-btn,
            .pagination,
            .table-scrollable {
                display: none !important;
            }

            .navbar,
            .form-inline {
                display: none !important;
            }

            .custom-container {
                max-width: 100%;
                margin: auto;
            }

            .custom-table th:last-child,
            .custom-table td:last-child {
                display: none;
            }
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">Controle de Veículos</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="usuarios.php">Usuários</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="veiculos.php">Veículo</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Sair</a>
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
    </form>

    <form class="form-inline mb-3 ml-auto">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAdicionarUsuario">Adicionar Novo Usuário</button>
        <button type="button" class="btn btn-primary ml-2" id="btnAtualizarTabela">Atualizar Tabela</button>
        <button type="button" class="btn btn-secondary ml-2 btn-imprimir-tabela btn-imprimir">Imprimir Tabela</button>
    </form>

    <div class=" table-responsive custom-container">
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
                <th style="white-space: nowrap;">Ações</th>
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
                    <td style="white-space: nowrap;"><?php echo $dados['data']; ?></td>
                    <td><?php echo $dados['cidade']; ?></td>
                    <td><?php echo $dados['localidade']; ?></td>
                    <td><?php echo $dados['cidade_02']; ?></td>
                    <td><?php echo $dados['localidade_02']; ?></td>
                    <td style="white-space: nowrap;">
                        <a href="#" class="btn btn-primary btn-sm">Visualizar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <ul class="pagination justify-content-center" style="margin-top: 7px;">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
            <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                <a class="page-link" href="?pagina=<?php echo $i . '&busca=' . urlencode($termoBusca); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</div>

<div class="modal fade" id="modalAdicionarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 375px;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdicionarUsuarioLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAdicionarUsuario" action="salvar_usuario.php" method="post">
                    <div class="form-group">
                        <label for="nomeNovoUsuario">Nome de Usuário</label>
                        <input type="text" class="form-control" id="nomeNovoUsuario" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="nomeCompletoNovoUsuario">Nome Completo</label>
                        <input type="text" class="form-control" id="nomeCompletoNovoUsuario" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="senhaNovoUsuario">Senha</label>
                        <input type="text" class="form-control" id="senhaNovoUsuario" placeholder="">
                    </div>

                    <div class="form-group ">
                        <label for="setorNovoUsuario">Setor</label>
                        <input type="text" class="form-control" id="setorNovoUsuario" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="telefoneNovoUsuario">Telefone</label>
                        <input type="text" class="form-control" id="telefoneNovoUsuario" placeholder="">
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary mx-auto">Adicionar Usuário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('#btnAtualizarTabela').click(function () {
            location.reload();
        });

        $('.btn-imprimir-tabela').click(function () {
            window.print();
        });

        $('#formAdicionarUsuario').submit(function (event) {
            event.preventDefault();
            var nomeNovoUsuario = $('#nomeNovoUsuario').val();
            var nomeCompletoNovoUsuario = $('#nomeCompletoNovoUsuario').val();
            var senhaNovoUsuario = $('#senhaNovoUsuario').val();
            var setorNovoUsuario = $('#setorNovoUsuario').val();
            var telefoneNovoUsuario = $('#telefoneNovoUsuario').val();

            $.ajax({
                type: 'POST',
                url: 'salvar_usuario.php',
                data: {
                    nomeNovoUsuario: nomeNovoUsuario,
                    nomeCompletoNovoUsuario: nomeCompletoNovoUsuario,
                    senhaNovoUsuario: senhaNovoUsuario,
                    setorNovoUsuario: setorNovoUsuario,
                    telefoneNovoUsuario: telefoneNovoUsuario
                },
                success: function (response) {
                    alert(response);
                    $('#modalAdicionarUsuario').modal('hide');
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });
</script>
</body>

</html>
