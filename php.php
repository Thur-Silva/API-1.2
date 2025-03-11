<?php
header("Content-type: application/json");

// Obter os dados do corpo da requisição
$data = file_get_contents("php://input");
$requestData = json_decode($data);

// Verificar se o Username e o Email foram enviados
if(!isset($requestData->Username) || !isset($requestData->Email)){
    echo json_encode(['error' => 'Username and Email are required']);
    exit;
}

$produto = $requestData->$Produto;
$tensao = $requestData->$Tensao;
$corrente = $requestData->$Corrente;

// Conectar ao banco de dados
$host = 'localhost';
$dbname = 'mytestedb';
$usernameDb = 'root';
$passwordDb = '';
$port = 3306;

$connection = new mysqli($host, $usernameDb, $passwordDb, $dbname, $port);

// Verificar se a conexão foi bem-sucedida
if($connection->connect_error){
    echo json_encode(['error' => 'Database connection failed: ' . $connection->connect_error]);
    exit;
}

// Preparar a query para inserir o usuário de maneira segura
$sql = "INSERT INTO users (produto, tensao, corrente) VALUES (?, ?, ?)";

// Usar prepared statement para evitar SQL injection
$stmt = $connection->prepare($sql);
if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare SQL statement']);
    exit;
}

// Bind dos parâmetros
$stmt->bind_param('ss', $produto, $tensao, $corrente);

// Executar a query
if($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User successfully added']);
} else {
    echo json_encode(['error' => 'Failed to add user']);
}

// Fechar a declaração e a conexão
$stmt->close();
$connection->close();
?>
