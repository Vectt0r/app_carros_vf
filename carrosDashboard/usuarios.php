<?php
session_start();

if (!isset($_SESSION["nome"])) {
    header("Location: index.php");
    exit();
}

$nome = $_SESSION["nome"];

function obterDadosDaAPI() {
    $apiUrl = 'http://localhost:3000/api/getCarrosUsuarios';
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

$termoBusca = isset($_GET['busca']) ? $_GET['busca'] : '';
$dadosFiltrados = [];

if (!empty($termoBusca)) {
    foreach ($dadosReais as $dados) {
        if (
            (isset($dados['nome']) && $dados['nome'] !== null && stripos($dados['nome'], $termoBusca) !== false) ||
            (isset($dados['setor']) && $dados['setor'] !== null && stripos($dados['setor'], $termoBusca) !== false) ||
            (isset($dados['placa']) && $dados['placa'] !== null && stripos($dados['placa'], $termoBusca) !== false)
        ) {
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
    <title>Controle de Veículos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        @media screen {
            body {
                padding-top: 100px;
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
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #146dc5!important">
    <a class="navbar-brand" href="#">
        <img src="imagens/logo_netcomet.png" alt="NetComet Logo" style="height: 50px;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
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
            <button type="button" class="btn btn-success ml-2" data-toggle="modal"
                    data-target="#modalAdicionarUsuario" style="margin-left: 7px;">Adicionar Usuário</button>
        </form>
    </form>

    <div>
        <table class="table table-striped table-hover custom-table">
            <thead>
            <tr>
                <th style="white-space: nowrap;">ID</th>
                <th style="white-space: nowrap;">Usuário</th>
                <th style="white-space: nowrap;">Nome Completo</th>
                <th style="white-space: nowrap;">Telefone</th>
                <th style="white-space: nowrap;">Setor</th>
                <th style="white-space: nowrap;width: 0px;">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dadosPaginaAtual as $dados) : ?>
                <tr>
                    <td style="white-space: nowrap;"><?php echo $dados['id']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['nome']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['nome_completo']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['telefone']; ?></td>
                    <td style="white-space: nowrap;"><?php echo $dados['setor']; ?></td>
                    <td style="white-space: nowrap;">

                        <!--BOTÕES DE ACÃO-->
                        <a href="#" class="btn btn-primary btn-sm btn-editar" data-toggle="modal"
                           data-target="#modalEditarUsuario_<?php echo $dados['id']; ?>"
                           data-id="<?php echo $dados['id']; ?>"><i class="material-icons">edit</i></a>
<!--                        <a href="#" class="btn btn-primary btn-sm btn-visualizar" data-toggle="modal"-->
<!--                           data-target="#modalVisualizarUsuario_--><?php //echo $dados['id']; ?><!--"-->
<!--                           data-id="--><?php //echo $dados['id']; ?><!--"><i class="material-icons">visibility</i></a>-->
                        <a href="#" class="btn btn-primary btn-sm"><i class="material-icons">delete</i></a>
                        <!--BOTÕES DE ACÃO-->

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div>
        <ul class="pagination justify-content-center" style="margin-top: 7px;">
            <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                    <a class="page-link"
                       href="?pagina=<?php echo $i . '&busca=' . urlencode($termoBusca); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>

    <!-- MODAL CADASTRAR -->
    <div class="modal fade" id="modalAdicionarUsuario" tabindex="-1" role="dialog"
         aria-labelledby="modalAdicionarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdicionarUsuarioLabel">Novo Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAdicionarUsuario" action="salvar_usuario.php" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nomeNovoUsuario">Usuário</label>
                                <input type="text" class="form-control" id="nomeNovoUsuario" name="nomeNovoUsuario"
                                       placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nomeCompletoNovoUsuario">Nome Completo</label>
                                <input type="text" class="form-control" id="nomeCompletoNovoUsuario"
                                       name="nomeCompletoNovoUsuario" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="senhaNovoUsuario">Senha</label>
                                <input type="password" class="form-control" id="senhaNovoUsuario"
                                       name="senhaNovoUsuario" placeholder="">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ConfirmarsenhaNovoUsuario">Confirmar Senha</label>
                                <input type="password" class="form-control" id="ConfirmarsenhaNovoUsuario"
                                       name="ConfirmarsenhaNovoUsuario" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="setorNovoUsuario">Setor</label>
                                <select id="setorNovoUsuario" name="setorNovoUsuario" class="form-control">
                                    <option selected>Administração</option>
                                    <option>Infraestrutura</option>
                                    <option>Suporte</option>
                                    <option>Instalação</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="telefoneNovoUsuario">Telefone</label>
                                <input type="text" class="form-control" id="telefoneNovoUsuario"
                                       name="telefoneNovoUsuario" placeholder="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL CADASTRAR -->

    <!-- MODAL VISUALIZAR -->
    <div class="modal fade" id="modalVisualizarUsuario" tabindex="-1" role="dialog"
         aria-labelledby="modalVisualizarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVisualizarUsuarioLabel">Detalhes do Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="visualizarUsuarioBody">
                    <!-- Os detalhes do usuário serão inseridos aqui dinamicamente via AJAX -->
                    <form id="formVisualizarUsuario" action="#" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="userName">Nome de Usuário</label>
                                <input type="text" class="form-control" id="userName" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="userFullName">Nome Completo</label>
                                <input type="text" class="form-control" id="userFullName" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="userSector">Setor</label>
                                <input type="text" class="form-control" id="userSector" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="userPhone">Telefone</label>
                                <input type="text" class="form-control" id="userPhone" readonly>
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
    <!-- MODAL VISUALIZAR -->

    <!-- MODAL EDITAR -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuário</h5>
                    <button type="button" id="botao_fechar" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="editarUsuarioBody">
                    <form id="formEditarUsuario" action="#" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="editUserName">Nome de Usuário</label>
                                <input type="text" class="form-control" id="editUserName" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editUserFullName">Nome Completo</label>
                                <input type="text" class="form-control" id="editUserFullName" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editUserPassword">Senha</label>
                                <input type="password" class="form-control" id="editUserPassword" name="editUserPassword" placeholder="Nova Senha" readonly >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editUserConfirmPassword">Confirmar Senha</label>
                                <input type="password" class="form-control" id="editUserConfirmPassword" name="editUserConfirmPassword" placeholder="Repetir Senha" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editUserSector">Setor</label>
                                <select id="editUserSector" name="editUserSector" class="form-control">
                                    <option selected>Administração</option>
                                    <option>Infraestrutura</option>
                                    <option>Suporte</option>
                                    <option>Instalação</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editUserPhone">Telefone</label>
                                <input type="tel" class="form-control" id="editUserPhone" name="editUserPhone">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="botao_fechar_modal"class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-danger btn-enable-edit-password">Editar Senha</button>
                            <button type="button" class="btn btn-success btn-salvar-edicao" data-id="">Salvar Edição</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL EDITAR -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#formAdicionarUsuario').submit(function (event) {
                event.preventDefault();
                var nomeNovoUsuario = $('#nomeNovoUsuario').val();
                var nomeCompletoNovoUsuario = $('#nomeCompletoNovoUsuario').val();
                var senhaNovoUsuario = $('#senhaNovoUsuario').val();
                var ConfirmarsenhaNovoUsuario = $('#ConfirmarsenhaNovoUsuario').val();
                var setorNovoUsuario = $('#setorNovoUsuario').val();
                var telefoneNovoUsuario = $('#telefoneNovoUsuario').val();

                //Verifique se todos os campos estão preenchidos
                if (!nomeNovoUsuario || !nomeCompletoNovoUsuario || !senhaNovoUsuario || !setorNovoUsuario || !telefoneNovoUsuario) {
                    alert('Por favor, preencha todos os campos antes de enviar.');
                    return;
                }

                //Confirmação e senha
                if(ConfirmarsenhaNovoUsuario != senhaNovoUsuario){
                    alert('As senhas digitadas nÃ£o coincidem')
                    return;
                }

                //Submeter AJAX se todos os campos estiverem preenchidos
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
                        $('#modalAdicionarUsuario input[type="text"]').val('');
                        $('#modalAdicionarUsuario input[type="password"]').val('');
                        $('#modalAdicionarUsuario select').val('');
                        $('#modalAdicionarUsuario [type="text"]').val('');
                        $('#modalAdicionarUsuario').modal('hide');
                        location.reload(); //recarrega a pagina automaticamente ao inserir um novo usuario
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $('.btn-visualizar').click(function () {
                var userId = $(this).data('id');

                $.ajax({
                    type: 'GET',
                    url: 'http://localhost:3000/api/getCarroUsuario/' + userId,
                    success: function (response) {
                        $('#userName').val(response.nome);
                        $('#userFullName').val(response.nome_completo);
                        $('#userSector').val(response.setor);
                        $('#userPhone').val(response.telefone);

                        $('#modalVisualizarUsuario').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $('.btn-editar').click(function () {
                var userId = $(this).data('id');

                $.ajax({
                    type: 'GET',
                    url: 'http://localhost:3000/api/getCarroUsuario/' + userId,
                    success: function (response) {
                        // Preencher os campos do formulário com os dados do usuário
                        $('#editUserName').val(response.nome);
                        $('#editUserFullName').val(response.nome_completo);
                        $('#editUserSector').val(response.setor);
                        $('#editUserPhone').val(response.telefone);
                        $('.btn-salvar-edicao').data('id', userId);

                        // Exibir o modal de edição
                        $('#modalEditarUsuario').modal('show');
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });


            $('.btn-salvar-edicao').click(function (event) {
                event.preventDefault();
                var userId = $(this).data('id');

                // Obtenha os valores dos campos editados
                var editUserPassword = $('#editUserPassword').val();
                var editUserConfirmPassword = $('#editUserConfirmPassword').val();
                var editUserSector = $('#editUserSector').val();
                var editUserPhone = $('#editUserPhone').val();

                // Trate os valores nulos ou vazios antes de enviar a atualização
                var requestData = {
                    senha: editUserPassword !== '' ? editUserPassword : null,
                    setor: editUserSector !== '' ? editUserSector : null,
                    telefone: editUserPhone !== '' ? editUserPhone : null
                };

                // Adicione os campos de nome e nome completo se estiverem presentes
                var editUserName = $('#editUserName').val();
                var editUserFullName = $('#editUserFullName').val();
                var editUserPhoneValue = $('#editUserPhone').val();

                if (editUserName) {
                    requestData.nome = editUserName;
                }

                if (editUserFullName) {
                    requestData.nome_completo = editUserFullName;
                }

                // Confirmação de senha
                if (editUserPassword !== editUserConfirmPassword) {
                    alert('As senhas não coincidem.');
                    return;
                }

                // Submeter AJAX para a nova rota de atualização
                $.ajax({
                    type: 'PUT',
                    url: 'http://localhost:3000/api/AtualizarUsuario/' + userId,
                    data: JSON.stringify(requestData),
                    contentType: 'application/json',
                    success: function (response) {
                        alert(response.message);
                        $('#modalEditarUsuario').modal('hide');
                        location.reload(); // Recarrega a página automaticamente ao editar um novo usuário
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });

            });

            // Botão fechar da modal de edição - reinicia a modal
            $('#botao_fechar, #botao_fechar_modal').click(function () {
                $('#editUserPassword, #editUserConfirmPassword').prop('readonly', true);
            });

            // Atualizar pagina
            $('#btnAtualizarTabela').click(function () {
                location.reload();
            });

            $('.btn-enable-edit-password').click(function () {
                $('#editUserPassword, #editUserConfirmPassword').prop('readonly', false);
            });
        });
    </script>
</body>
</html>
