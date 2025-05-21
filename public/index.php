<?php
require_once __DIR__ . '/../src/controllers/ContaController.php';
require_once __DIR__ . '/../src/controllers/TransacaoController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if (is_file($file) || (is_dir($file) && is_file($file.'/index.html'))) {
    return false;  // deixa o Apache servir o arquivo estático
}

// Se for um diretório contendo index.html, também deixa o Apache servir
if (is_dir($file) && is_file($file . '/index.html')) {
    return false;
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/conta' && $method === 'POST') {
    (new ContaController())->criarConta();

} elseif ($path === '/conta' && $method === 'GET') {
    (new ContaController())->buscarConta();

} elseif ($path === '/transacao' && $method === 'POST') {
    (new TransacaoController())->realizarTransacao();

} else {
    http_response_code(404);
    echo json_encode(["erro" => "Endpoint não encontrado"]);
}
