<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/services/ContaService.php';
require_once __DIR__ . '/../src/services/TransacaoService.php';
require_once __DIR__ . '/../src/database/Connection.php';

class ContaServiceTest extends TestCase
{
    private $service;
    private $numeroConta = 9998;

    protected function setUp(): void
    {
        $this->service = new ContaService();
        $conn = Connection::getConnection();
        $conn->prepare("DELETE FROM contas WHERE numero_conta = ?")->execute([$this->numeroConta]);
    }

    public function testContaNaoExiste()
    {
        $this->assertFalse($this->service->contaExiste($this->numeroConta), "Conta inexistente deve retornar falso");
    }

    public function testCriarContaERetornarComSucesso()
    {
        $this->service->criarConta($this->numeroConta, 100.00);
        $resultado = $this->service->contaExiste($this->numeroConta);
        $this->assertNotFalse($resultado, "Conta criada deve ser encontrada no banco");
        $this->assertEquals(100.00, $resultado['saldo'], "Saldo deve ser igual ao valor informado");
    }
}