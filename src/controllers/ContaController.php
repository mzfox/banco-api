<?php
require_once __DIR__ . '/../services/ContaService.php';

class ContaController {
    private $service;

    public function __construct() {
        $this->service = new ContaService();
    }

    public function criarConta() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['numero_conta']) || !isset($data['saldo'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Campos obrigatórios ausentes"]);
            return;
        }

        $numero_conta = $data['numero_conta'];
        $saldo = $data['saldo'];

        if ($this->service->contaExiste($numero_conta)) {
            http_response_code(409);
            echo json_encode(["erro" => "Conta já existe"]);
            return;
        }

        $this->service->criarConta($numero_conta, $saldo);
        http_response_code(201);
        echo json_encode(["numero_conta" => $numero_conta, "saldo" => $saldo]);
    }

    public function buscarConta() {
        if (!isset($_GET['numero_conta'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Número da conta é obrigatório"]);
            return;
        }

        $numero_conta = $_GET['numero_conta'];
        $conta = $this->service->contaExiste($numero_conta);

        if (!$conta) {
            http_response_code(404);
            echo json_encode(["erro" => "Conta não encontrada"]);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "numero_conta" => $conta['numero_conta'],
            "saldo" => $conta['saldo']
        ]);
    }
}