DOCUMENTAÇÃO DA API - INSERÇÃO DE DADOS NO BANCO DE DADOS

1. INTRODUÇÃO
Esta API tem como finalidade a recepção de dados JSON contendo informações sobre produto, tensão e corrente, para posterior inserção em um banco de dados MySQL.
A API recebe uma requisição HTTP POST, valida os dados e os armazena na tabela users.
2. REQUISITOS
•	Servidor com suporte a PHP (>=7.4 recomendado);
•	Banco de Dados MySQL configurado;
•	Servidor local (XAMPP, WAMP) ou servidor remoto;
•	Ambiente de desenvolvimento compatível (ex: Visual Studio, Postman para testes, etc.).
3. ESTRUTURA DA API
3.1. URL DO ENDPOINT
http://localhost/api/php.php
3.2. MÉTODO HTTP SUPORTADO
•	POST (envia dados para o banco de dados).
3.3. PARÂMETROS DE REQUISIÇÃO
A API recebe um JSON no corpo da requisição com a seguinte estrutura:
{
    "Produto": "Nome do Produto",
    "Tensao": "220V",
    "Corrente": "10A"
}

3.4. EXEMPLO DE REQUISIÇÃO
Usando o Postman:
1.	Selecione o método POST;
2.	No campo Body, escolha a opção raw e selecione JSON;
3.	Insira o JSON de requisição acima;
4.	Clique em Send.
3.5. RESPOSTAS DA API
A API retorna um JSON como resposta, podendo indicar sucesso ou erro.
3.5.1. Sucesso
Caso os dados sejam inseridos corretamente:
{
    "success": true,
    "message": "User successfully added"
}
3.5.2. Erros
Código HTTP	Mensagem de Erro	Causa
400	Produto, Tensão e Corrente são obrigatórios	Algum dos campos obrigatórios não foi enviado
500	Database connection failed	Erro na conexão com o banco de dados
500	Failed to prepare SQL statement	Falha na preparação da query SQL
500	Failed to add user	Erro ao executar a inserção
4. BANCO DE DADOS
4.1. CONFIGURAÇÃO DO BANCO
A API utiliza um banco de dados MySQL com a seguinte estrutura:
CREATE DATABASE mytestedb;

USE mytestedb;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto VARCHAR(255) NOT NULL,
    tensao VARCHAR(50) NOT NULL,
    corrente VARCHAR(50) NOT NULL
);
4.2. CREDENCIAIS DO BANCO
As credenciais de acesso ao banco estão configuradas no arquivo PHP:
$host = 'localhost';
$dbname = 'mytestedb';
$usernameDb = 'root';
$passwordDb = '';
$port = 3306;
5. INTEGRAÇÃO COM C# (VISUAL STUDIO)
Para consumir essa API em um projeto C# .NET, utilizamos a classe HttpClient. Abaixo está um código de exemplo:
using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

class Program
{
    static async Task Main(string[] args)
    {
        using (HttpClient client = new HttpClient())
        {
            string url = "http://localhost/api/php.php";
            
            var json = "{\"Produto\":\"Furadeira\", \"Tensao\":\"220V\", \"Corrente\":\"10A\"}";
            var content = new StringContent(json, Encoding.UTF8, "application/json");

            HttpResponseMessage response = await client.PostAsync(url, content);
            
            string result = await response.Content.ReadAsStringAsync();
            Console.WriteLine(result);
        }
    }
}
Explicação do Código:
•	Criamos um objeto HttpClient para enviar a requisição;
•	Definimos a URL da API;
•	Criamos um JSON com os dados do produto;
•	Enviamos a requisição usando PostAsync();
•	Capturamos e exibimos a resposta no console.
6. CONCLUSÃO
Esta API foi desenvolvida para receber dados JSON e armazená-los no MySQL, garantindo segurança por meio de prepared statements. A integração com C# .NET pode ser feita utilizando a classe HttpClient, facilitando a comunicação entre aplicações.

