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

        .logo-impressao {
            display: none;
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

            .logo-impressao {
                display: block;
                text-align: center;
                margin-bottom: 20px;
                max-width: 100%;
                height: auto;
            }

            .custom-table th:last-child,
            .custom-table td:last-child {
                display: none;
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
                <a class="nav-link" href="">
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
<div class="logo-impressao">
    <img src="imagens/logo_netcomet.png" alt="NetComet Logo" style="height: 50px;">
</div>
<div class="container">
    <form class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="busca" class="sr-only">Buscar</label>
            <input type="text" class="form-control" id="busca" name="busca" placeholder="Digite para buscar">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
        <form class="form-inline mb-3 ml-auto">
            <button type="button" class="btn btn-primary ml-2" id="btnAtualizarTabela">Atualizar Tabela</button>
        <button type="button" class="btn btn-secondary ml-2 btn-imprimir-tabela btn-imprimir">Imprimir Tabela</button>
        </form>
    </form>

    <!--ADICIONAR SCROLL INTERNO <div style="height: 680px; overflow-y: auto;">-->
    <div>
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
                    <td style="white-space: nowrap;">
                        <a href="#" class="btn btn-primary btn-sm btn-editar" data-toggle="modal" data-target="#ModalEditar" data-id="<?php echo $dados['id']; ?>"><i class="material-icons">edit</i></a>
                        <a href="#" class="btn btn-primary btn-sm btn-visualizar" data-toggle="modal" data-target=".bd-example-modal-lg" data-id="<?php echo $dados['id']; ?>"><i class="material-icons">search</i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!--//Modal Vizualizar-->
    <div class="modal fade bd-example-modal-lg" id='ModalVisualizar' tabindex="-1" role="dialog"
         aria-labelledby="ModalVisualizarlLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalVisualizarlLabel">Detalhes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nome_funcionario">Funcionário:</label>
                                <input type="text" class="form-control" id="nome_funcionario" placeholder="" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="data">Data:</label>
                                <input type="" class="form-control" id="data" placeholder="" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="placa">Placa:</label>
                                <input type="text" class="form-control" id="placa" placeholder="" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="kmInicial">KM Inicial:</label>
                                <input type="text" class="form-control" id="kmInicial" placeholder="" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="kmFinal">KM Final:</label>
                                <input type="number" class="form-control" id="kmFinal" placeholder="" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="horaSaida">Hora da Saida:</label>
                                <input type="text" class="form-control" id="horaSaida" placeholder="" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="horaChegada">Hora da Chegada:</label>
                                <input type="text" class="form-control" id="horaChegada" placeholder="" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cidade">Cidade:</label>
                                <input type="text" class="form-control" id="cidade" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="localidade">Localidade:</label>
                                <input type="text" class="form-control" id="localidade" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cidadeDois">Cidade 2:</label>
                                <input type="text" class="form-control" id="cidadeDois" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="localidadeDois">Localidade 2:</label>
                                <input type="text" class="form-control" id="localidadeDois" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!--//Modal Vizualizar-->

    <!--//Modal Editar-->
    <div class="modal fade bd-example-modal-lg" id='ModalEditar' tabindex="-1" role="dialog"
         aria-labelledby="ModalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEditarLabel">Detalhes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nome_funcionario_editar">Funcionário:</label>
                                <input type="text" class="form-control" id="nome_funcionario_editar" placeholder="" >
                            </div>
                            <div class="form-group col-md-4">
                                <label for="data_editar">Data:</label>
                                <input type="text" class="form-control" id="data_editar" placeholder="" >
                            </div>
                            <div class="form-group col-md-4">
                                <label for="placa_editar">Placa:</label>
                                <input type="text" class="form-control" id="placa_editar" placeholder="" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="kmInicial_editar">KM Inicial:</label>
                                <input type="number" class="form-control" id="kmInicial_editar" placeholder="" >
                            </div>
                            <div class="form-group col-md-3">
                                <label for="kmFinal_editar">KM Final:</label>
                                <input type="number" class="form-control" id="kmFinal_editar" placeholder="" >
                            </div>
                            <div class="form-group col-md-3">
                                <label for="horaSaida_editar">Hora da Saida:</label>
                                <input type="time" class="form-control" id="horaSaida_editar" placeholder="" >
                            </div>
                            <div class="form-group col-md-3">
                                <label for="horaChegada_editar">Hora da Chegada:</label>
                                <input type="time" class="form-control" id="horaChegada_editar" placeholder="" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cidade_editar">Cidade:</label>
                                <input type="text" class="form-control" id="cidade_editar" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="localidade_editar">Localidade:</label>
                                <input type="text" class="form-control" id="localidade_editar" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cidadeDois_editar">Cidade 2:</label>
                                <input type="text" class="form-control" id="cidadeDois_editar" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="localidadeDois_editar">Localidade 2:</label>
                                <input type="text" class="form-control" id="localidadeDois_editar" >
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success btn-salvar-edicao" data-id="">Salvar Edição</button>
                </div>
            </div>
        </div>
    </div>
    <!--//Modal Editar-->

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#btnAtualizarTabela').click(function () {
                location.reload();
            });

            $('.btn-imprimir-tabela').click(function () {
                window.print();
            });

            $('.btn-visualizar').click(function () {
                var userId = $(this).data('id');

                $.ajax({
                    type: 'GET',
                    url: 'http://localhost:3000/api/getCarroControle/' + userId,
                    success: function (response) {
                        $('#nome_funcionario').val(response.nome_funcionario);
                        $('#placa').val(response.placa);
                        $('#kmInicial').val(response.km_inicial);
                        $('#kmFinal').val(response.km_final);
                        $('#horaSaida').val(response.hora_saida);
                        $('#horaChegada').val(response.hora_chegada);
                        $('#cidade').val(response.cidade);
                        $('#localidade').val(response.localidade);
                        $('#cidadeDois').val(response.cidade_02);
                        $('#localidadeDois').val(response.localidade_02);

                        console.log(response.data);

                        if (response.data) {
                            var dataFormatada = formatarData(response.data);
                            $('#data').val(dataFormatada);
                        }

                        $('#ModalVisualizar').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });

                function formatarData(dataISO) {
                    const data = new Date(dataISO);
                    const dia = String(data.getDate()).padStart(2, '0');
                    const mes = String(data.getMonth() + 1).padStart(2, '0');
                    const ano = data.getFullYear();
                    return `${dia}/${mes}/${ano}`;
                }
            });


            $('.btn-editar').click(function () {
                var userId = $(this).data('id');

                $.ajax({
                    type: 'GET',
                    url: 'http://localhost:3000/api/getCarroControle/' + userId,
                    success: function (response) {
                        $('#nome_funcionario_editar').val(response.nome_funcionario);

                        if (response.data) {
                            var dataFormatada = formatarData(response.data);
                            $('#data_editar').val(dataFormatada);
                            $('#data_editar').mask('00/00/0000'); //mascara
                        }

                        $('#placa_editar').val(response.placa);
                        $('#kmInicial_editar').val(response.km_inicial);
                        $('#kmFinal_editar').val(response.km_final);
                        $('#horaSaida_editar').val(response.hora_saida);
                        $('#horaChegada_editar').val(response.hora_chegada);
                        $('#cidade_editar').val(response.cidade);
                        $('#localidade_editar').val(response.localidade);
                        $('#cidadeDois_editar').val(response.cidade_02);
                        $('#localidadeDois_editar').val(response.cidade_02);

                        $('.btn-salvar-edicao').data('id', userId);

                        $('#modalEditarCorrida').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $('.btn-salvar-edicao').click(function (event) {
                event.preventDefault();
                var userId = $(this).data('id');

                var nome_funcionario_editar = $('#nome_funcionario_editar').val();
                var data_editar = $('#data_editar').val();
                var placa_editar = $('#placa_editar').val();
                var kmInicial_editar = $('#kmInicial_editar').val();
                var kmFinal_editar = $('#kmFinal_editar').val();
                var horaSaida_editar = $('#horaSaida_editar').val();
                var horaChegada_editar = $('#horaChegada_editar').val();
                var cidade_editar = $('#cidade_editar').val();
                var localidade_editar = $('#localidade_editar').val();
                var cidadeDois_editar = $('#cidadeDois_editar').val();
                var localidadeDois_editar = $('#localidadeDois_editar').val();

                var requestData = {
                    nome_funcionario: nome_funcionario_editar,
                    data: data_editar,
                    placa: placa_editar,
                    kmInicial: kmInicial_editar,
                    kmFinal: kmFinal_editar,
                    horaSaida: horaSaida_editar,
                    horaChegada: horaChegada_editar,
                    cidade: cidade_editar,
                    localidade: localidade_editar,
                    cidade_dois: cidadeDois_editar,
                    localidade_dois: localidadeDois_editar
                };

                $.ajax({
                    type: 'PUT',
                    url: 'http://localhost:3000/api/AtualizarCarro/' + userId,
                    data: JSON.stringify(requestData),
                    contentType: 'application/json',
                    success: function (response) {
                        alert(response.message);
                        $('#modalEditarCorrida').modal('hide');
                        location.reload();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            function formatarData(dataISO) {
                const data = new Date(dataISO);
                const dia = String(data.getDate()).padStart(2, '0');
                const mes = String(data.getMonth() + 1).padStart(2, '0');
                const ano = data.getFullYear();
                return `${dia}/${mes}/${ano}`;
            }
        });
    </script>
</body>
</html>
