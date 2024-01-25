// index.js

const express = require('express');
const cors = require('cors');
const routes = require('./routes'); // Caminho para o seu arquivo de rotas

const app = express();
const port = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());

app.use('/api', routes); // Define o prefixo '/api' para suas rotas

app.listen(port, () => {
  console.log(`Servidor rodando em http://localhost:${port}`);
});
