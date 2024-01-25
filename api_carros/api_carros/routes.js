// routes.js

const express = require('express');
const router = express.Router();
const mysql = require('mysql2');
const cors = require('cors');
const jwt = require('jsonwebtoken');

const connection = mysql.createConnection({
  host: '127.0.0.1',
  user: 'root',
  password: '',
  database: 'carros_app',
});

// Middleware para habilitar o CORS
router.use(cors());
router.use(express.json());

// Rota para salvar os dados da corrida
router.post('/encerrarCorrida', (req, res) => {
  const {
    nome,
    placa,
    kmInicial,
    kmFinal,
    horaSaida,
    horaChegada,
    data,
    cidade,
    cidadeDois,
    localidade,
    localidadeDois,
    status,
  } = req.body;

  console.log('Dados recebidos no servidor:', {
    nome,
    placa,
    kmInicial,
    kmFinal,
    horaSaida,
    horaChegada,
    data,
    cidade,
    localidade,
    cidadeDois,
    localidadeDois,
  });

  const query =
    'INSERT INTO carros_controle (nome_funcionario, placa, km_inicial, km_final, hora_saida, hora_chegada, data, cidade, localidade, cidade_02, localidade_02, status) VALUES (?, ?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?)';
  connection.query(
    query,
    [nome, placa, kmInicial, kmFinal, horaSaida, horaChegada, data, cidade, localidade, cidadeDois, localidadeDois, status],
    (error, results) => {
      if (error) {
        console.error('Erro ao inserir dados no banco de dados:', error);
        res.status(500).json({ error: 'Erro ao inserir dados no banco de dados' });
      } else {
        res.json({ success: true, message: 'Dados inseridos com sucesso' });
      }
    }
  );
});

// Rota para obter todos os registros
router.get('/getCarrosControle', (req, res) => {
    const query = 'SELECT * FROM carros_controle';
  
    connection.query(query, (error, results) => {
      if (error) {
        console.error('Erro ao obter dados do banco de dados:', error);
        res.status(500).json({ error: 'Erro ao obter dados do banco de dados' });
      } else {
        res.json(results);
      }
    });
  });


// Rota para obter um registro pelo ID
router.get('/getCarroControle/:id', (req, res) => {
    const carroId = req.params.id;
  
    const query = 'SELECT * FROM carros_controle WHERE id = ?';
  
    connection.query(query, [carroId], (error, results) => {
      if (error) {
        console.error('Erro ao obter dados do banco de dados:', error);
        res.status(500).json({ error: 'Erro ao obter dados do banco de dados' });
      } else {
        if (results.length > 0) {
          res.json(results[0]); // Retorna o primeiro registro encontrado
        } else {
          res.status(404).json({ error: 'Registro não encontrado' });
        }
      }
    });
  });

// Rota para Cadastrar novo usuario
router.post('/CadastrarNovoUsuario', (req, res) => {
  const {
    nome,
    senha,
    setor,
    telefone,
  } = req.body;

  console.log('Dados recebidos no servidor:', {
    nome,
    senha,
    setor,
    telefone, 
  });

  const query =
    'INSERT INTO carros_usuarios (nome, senha, setor, telefone) VALUES (? , ?, ?, ?)';
  connection.query(
    query,
    [nome, senha, setor, telefone],
    (error, results) => {
      if (error) {
        console.error('Erro ao inserir dados no banco de dados:', error);
        res.status(500).json({ error: 'Erro ao inserir dados no banco de dados' });
      } else {
        res.json({ success: true, message: 'Dados inseridos com sucesso' });
      }
    }
  );
});

// Rota para autenticar um usuário
router.post('/login', (req, res) => {
  const { nome, senha } = req.body;

  const query = 'SELECT * FROM carros_usuarios WHERE nome = ? AND senha = ?';

  connection.query(query, [nome, senha], (error, results) => {
    if (error) {
      console.error('Erro ao autenticar usuário:', error);
      res.status(500).json({ error: 'Erro ao autenticar usuário' });
    } else {
      if (results.length > 0) {
        // Usuário autenticado, gerar token de acesso
        const userId = results[0].id; // Assumindo que o ID do usuário está na coluna 'id'
        const token = jwt.sign({ userId }, 'seuSegredo'); // Substitua 'seuSegredo' por uma chave segura

        res.json({ success: true, message: 'Usuário autenticado com sucesso', token });
      } else {
        res.status(401).json({ error: 'Credenciais inválidas' });
      }
    }
  });
});

module.exports = router;

//rota login
//http://localhost:3000/api//login

//rota exibir dado
//http://localhost:3000/api/getCarroControle/23

//rota exibir todos os registros
//http://localhost:3000/api/getCarrosControle
