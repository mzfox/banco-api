<?php
require_once __DIR__ . '/../database/Connection.php';

class ContaService {
    private $conn;

    public function __construct() {
        $this->conn = Connection::getConnection();
    }

    public function contaExiste($numero_conta) {
        $stmt = $this->conn->prepare("SELECT * FROM contas WHERE numero_conta = ?");
        $stmt->execute([$numero_conta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criarConta($numero_conta, $saldo) {
        $stmt = $this->conn->prepare("INSERT INTO contas (numero_conta, saldo) VALUES (?, ?)");
        $stmt->execute([$numero_conta, $saldo]);
    }
}