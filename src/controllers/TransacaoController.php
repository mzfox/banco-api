<?php
require_once __DIR__ . '/../services/TransacaoService.php';

class TransacaoController {
    private $service;

    public function __construct() {
        $this->service = new TransacaoService();
    }

    public function realizarTransacao() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['numero_conta'], $data['valor'], $data['forma_pagamento'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Campos obrigatórios ausentes"]);
            return;
        }

        $conta = $this->service->buscarConta($data['numero_conta']);
        if (!$conta) {
            http_response_code(404);
            echo json_encode(["erro" => "Conta não encontrada"]);
            return;
        }

        $valor_com_taxa = $this->service->aplicarTaxa($data['valor'], $data['forma_pagamento']);

        if ($valor_com_taxa === null) {
            http_response_code(400);
            echo json_encode(["erro" => "Forma de pagamento inválida"]);
            return;
        }

        if ($conta['saldo'] < $valor_com_taxa) {
            http_response_code(404);
            echo json_encode(["erro" => "Saldo insuficiente"]);
            return;
        }

        $novo_saldo = round($conta['saldo'] - $valor_com_taxa, 2);
        $this->service->atualizarSaldo($data['numero_conta'], $novo_saldo);

        http_response_code(201);
        echo json_encode([
            "numero_conta" => $data['numero_conta'],
            "saldo" => $novo_saldo
        ]);
    }
}