<?php
session_start();
if (isset($_SESSION['erroLogin']) && $_SESSION['erroLogin']) {
    echo '<script>document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("errorModal").style.display = "flex";
    });</script>';
    unset($_SESSION['erroLogin']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>NetComet Carros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Adicione o link para o Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('imagens/fundo_netcomet_4.png');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            height: 100vh;
        }

        .login-container {
            width: 80%;
            max-width: 400px;
            padding: 40px;
            border-radius: 11px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            opacity: 2.9;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 18px;
            font-weight: bold;
            color: #100f0f;
        }

        input {
            padding: 10px;
            border: 1px solid #000;
            border-radius: 10px;
            font-size: 10px;
        }

        input::placeholder {
            font-size: 15px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .checkbox-label {
            font-size: 16px;
            color: #100f0f;
            margin-left: 10px;
            margin-bottom: 1px;
        }

        .checkbox-input {
            margin: 0;
        }

        .button-container {
            margin-top: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4682B4;
            color: #fff;
            border: none;
            margin-top: 10px;
            border-radius: 10px;
            font-size: 25px;
            text-align: center;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
            text-align: center;
        }

        button:focus {
            outline: none;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            max-width: 25%;
            max-height: 25%;
            overflow: auto;
            text-align: center;
        }

        #termosLabel:hover {
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="post" action="acao_login.php" id="loginForm">
            <div class="form-group">
                <label for="nome">Usuário:</label>
                <input type="text" class="form-control" name="nome" id="nome" placeholder="Digite Seu Nome" oninput="validateName(this);" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" required>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" id="termos" name="termos" class="checkbox-input">
                <label class="checkbox-label" for="termos" id="termosLabel">Manter Login</label>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary">Conectar</button>
            </div>
        </form>
    </div>

    <div class="modal-overlay" id="errorModal" <?php if(isset($_SESSION['erroLogin']) && $_SESSION['erroLogin']) echo 'style="display: flex;"'; ?>>
        <div class="modal-content">
            <h2>Erro de Autenticação</h2>
            <p>Usuário ou Senha inválido. Por favor, tente novamente.</p>
            <button id="fecharErroModalBtn" class="btn btn-secondary modal-button">Fechar</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        const errorModal = document.getElementById("errorModal");
        const fecharErroModalBtn = document.getElementById("fecharErroModalBtn");

        fecharErroModalBtn.addEventListener("click", () => {
            errorModal.style.display = "none";
        });

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("loginForm");
            const nomeInput = document.getElementById("nome");

            // Tenta recuperar o nome do usuário do localStorage
            const nomeArmazenado = localStorage.getItem("nomeUsuario");

            // Se houver um nome de usuário armazenado, preenche automaticamente o campo de nome
            if (nomeArmazenado) {
                nomeInput.value = nomeArmazenado;
                // Verifica se a opção "Manter Login" estava marcada
                manterLoginCheckbox.checked = true;
            }

            // Adiciona um ouvinte de evento para o envio do formulário
            form.addEventListener("submit", function () {
                // Armazena o nome do usuário no localStorage se a opção "Manter Login" estiver marcada
                if (manterLoginCheckbox.checked) {
                    localStorage.setItem("nomeUsuario", nomeInput.value);
                } else {
                    // Limpa o localStorage se a opção "Manter Login" não estiver marcada
                    localStorage.removeItem("nomeUsuario");
                }
            });
        });
    </script>
</body>
</html>
