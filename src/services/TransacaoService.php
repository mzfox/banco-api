<?php
require_once __DIR__ . '/../database/Connection.php';

class TransacaoService {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getConnection();
    }

    public function buscarConta($numero_conta) {
        $stmt = $this->conn->prepare("SELECT * FROM contas WHERE numero_conta = ?");
        $stmt->execute([$numero_conta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarSaldo($numero_conta, $novo_saldo) {
        $stmt = $this->conn->prepare("UPDATE contas SET saldo = ? WHERE numero_conta = ?");
        $stmt->execute([$novo_saldo, $numero_conta]);
    }

    public function aplicarTaxa($valor, $forma_pagamento) {
        switch ($forma_pagamento) {
            case 'D': return round($valor * 1.03, 2); // débito
            case 'C': return round($valor * 1.05, 2); // crédito
            case 'P': return round($valor, 2);        // pix
            default: return null;
        }
    }
}