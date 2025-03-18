DOCUMENTAÇÃO DA API - INSERÇÃO DE DADOS NO BANCO DE DADOS

1. INTRODUÇÃO
Esta API foi desenvolvida para receber dados via HTTP POST no formato JSON, contendo informações referentes a Produto, Tensão e Corrente, e para inseri-los de forma segura em um banco de dados MySQL. A comunicação entre clientes e a API é feita via JSON, e os dados são inseridos na tabela users por meio de prepared statements, garantindo proteção contra ataques de SQL Injection.

2. REQUISITOS
Servidor PHP: Versão 7.4 ou superior.
Banco de Dados MySQL: Com a base de dados mytestedb e a tabela users.
Ambiente de Desenvolvimento: Pode ser um servidor local (XAMPP, WAMP, etc.) ou servidor remoto.
Ferramentas para Teste: Postman ou qualquer outro cliente HTTP para testar a API.
3. ESTRUTURA DA API
3.1. URL DO ENDPOINT
arduino
Copiar
Editar
http://localhost:8080/API/Primeira_API_POST.php
3.2. MÉTODO HTTP SUPORTADO
POST – utilizado para enviar os dados a serem inseridos no banco de dados.
3.3. PARÂMETROS DE REQUISIÇÃO
A API espera receber um objeto JSON com os seguintes campos:

Campo	Tipo	Obrigatório	Descrição
Produto	String	Sim	Nome ou identificação do produto.
Tensao	String	Sim	Valor da tensão (ex.: "220V").
Corrente	String	Sim	Valor da corrente (ex.: "10A").
Exemplo de JSON:
json
Copiar
Editar
{
    "Produto": "Furadeira",
    "Tensao": "220V",
    "Corrente": "10A"
}
3.4. VALIDAÇÃO
Antes de inserir os dados, a API verifica se os campos Produto, Tensao e Corrente foram enviados. Caso algum desses campos esteja ausente, é retornado um erro com o código HTTP 400.

3.5. RESPOSTAS DA API
3.5.1. Resposta de Sucesso
Caso os dados sejam inseridos corretamente:

json
Copiar
Editar
{
    "success": true,
    "message": "User successfully added"
}
3.5.2. Respostas de Erro
Código HTTP	Mensagem de Erro	Descrição
400	Produto, Tensão e Corrente são obrigatórios	Algum dos campos obrigatórios não foi informado.
500	Database connection failed	Erro na conexão com o banco de dados.
500	Failed to prepare SQL statement	Erro ao preparar a query SQL.
500	Failed to add user	Erro ao executar a query de inserção no banco de dados.
4. CONFIGURAÇÃO DO BANCO DE DADOS
A API utiliza a seguinte configuração para conexão ao banco de dados:

php
Copiar
Editar
$host = 'localhost';
$dbname = 'mytestedb';
$usernameDb = 'root';
$passwordDb = '';
$port = 3306;
A tabela users pode ser criada com o seguinte script SQL:

sql
Copiar
Editar
CREATE DATABASE mytestedb;

USE mytestedb;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto VARCHAR(255) NOT NULL,
    tensao VARCHAR(50) NOT NULL,
    corrente VARCHAR(50) NOT NULL
);
5. INTEGRAÇÃO COM C# (VISUAL STUDIO)
Para facilitar a integração da API com aplicações desenvolvidas em C#, segue um exemplo de implementação utilizando o HttpClient e a biblioteca Newtonsoft.Json.

5.1. CÓDIGO EXEMPLO EM C#
csharp
Copiar
Editar
using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using Newtonsoft.Json;

namespace Recebe_API
{
    public partial class Form1 : Form
    {
        // URL do endpoint da API
        private static string apiUrl = "http://localhost:8080/API/Primeira_API_POST.php";

        public Form1()
        {
            InitializeComponent();
        }

        // Método para enviar requisição POST para a API
        public static async Task EnviarPostAsync(string json)
        {
            using (HttpClient httpClient = new HttpClient())
            {
                var content = new StringContent(json, Encoding.UTF8, "application/json");

                try
                {
                    var response = await httpClient.PostAsync(apiUrl, content);

                    if (response.IsSuccessStatusCode)
                    {
                        string result = await response.Content.ReadAsStringAsync();
                        MessageBox.Show($"Resposta da API: {result}");
                    }
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message);
                }
            }
        }

        private async void button1_Click(object sender, EventArgs e)
        {
            label4.Visible = true;

            string produto = textBox1.Text;
            string tensao = textBox2.Text;
            string corrente = textBox3.Text;

            try
            {
                Comunicacao_API novoItem = new Comunicacao_API(produto, tensao, corrente);
                string json = JsonConvert.SerializeObject(novoItem);
                await EnviarPostAsync(json);
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }
    }
}
5.2. EXPLICAÇÃO DO CÓDIGO C#
HttpClient: Utilizado para enviar a requisição HTTP POST para a API.
JsonConvert.SerializeObject: Converte o objeto que contém os dados (produto, tensão e corrente) em uma string JSON.
StringContent: Prepara o conteúdo da requisição, definindo o tipo como "application/json".
PostAsync: Método assíncrono que envia a requisição à URL da API.
Tratamento de Erros: São exibidas mensagens de erro por meio do MessageBox.
6. CONCLUSÃO
Esta documentação apresenta a API desenvolvida em PHP para a inserção de dados no banco de dados, detalhando as etapas de requisição, resposta e integração com aplicações em C#. A API foi projetada com foco na segurança e na validação dos dados, utilizando prepared statements para evitar ataques de SQL Injection. A integração via C# utiliza o HttpClient e a biblioteca Newtonsoft.Json para facilitar a comunicação e manipulação dos dados em projetos desenvolvidos no Visual Studio.
