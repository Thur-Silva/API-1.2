<?php
header("Content-type: application/json");

// Obter os dados do corpo da requisição
$data = file_get_contents("php://input");
$requestData = json_decode($data);

// Verificar se os campos obrigatórios foram enviados
if (!isset($requestData->Produto) || !isset($requestData->Tensao) || !isset($requestData->Corrente)) {
    http_response_code(400); // Código de erro HTTP
    echo json_encode(['error' => 'Produto, Tensão e Corrente são obrigatórios']);
    exit;
}

$produto = $requestData->Produto;
$tensao = $requestData->Tensao;
$corrente = $requestData->Corrente;

// Conectar ao banco de dados
$host = 'localhost';
$dbname = 'mytestedb';
$usernameDb = 'root';
$passwordDb = '';
$port = 3306;

$connection = new mysqli($host, $usernameDb, $passwordDb, $dbname, $port);

// Verificar se a conexão foi bem-sucedida
if ($connection->connect_error) {
    http_response_code(500); // Código de erro interno do servidor
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Preparar a query para inserir o usuário de maneira segura
$sql = "INSERT INTO users (produto, tensao, corrente) VALUES (?, ?, ?)";
$stmt = $connection->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare SQL statement']);
    exit;
}

// Bind dos parâmetros
$stmt->bind_param('sss', $produto, $tensao, $corrente);

// Executar a query
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User successfully added']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add user']);
}

// Fechar a declaração e a conexão
$stmt->close();
$connection->close();
?>
