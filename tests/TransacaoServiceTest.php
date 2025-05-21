<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/services/TransacaoService.php';
require_once __DIR__ . '/../src/services/ContaService.php';
require_once __DIR__ . '/../src/database/Connection.php';

class TransacaoServiceTest extends TestCase
{
    private $service;
    private $numeroConta = 9999;

    protected function setUp(): void
    {
        $this->service = new TransacaoService();
        $conn = Connection::getConnection();
        $conn->prepare("DELETE FROM contas WHERE numero_conta = ?")->execute([$this->numeroConta]);

        $contaService = new ContaService();
        $contaService->criarConta($this->numeroConta, 100.00);
    }

    public function testAplicaTaxaDebito()
    {
        $this->assertEquals(10.30, $this->service->aplicarTaxa(10, 'D'));
    }

    public function testAplicaTaxaCredito()
    {
        $this->assertEquals(10.50, $this->service->aplicarTaxa(10, 'C'));
    }

    public function testAplicaTaxaPix()
    {
        $this->assertEquals(10.00, $this->service->aplicarTaxa(10, 'P'));
    }

    public function testTransacaoComSaldoInsuficiente()
    {
        $conta = $this->service->buscarConta($this->numeroConta);
        $valor = $this->service->aplicarTaxa(1000, 'C');
        $this->assertTrue($conta['saldo'] < $valor);
    }

    public function testTransacaoBemSucedidaAtualizaSaldo()
    {
        $conta = $this->service->buscarConta($this->numeroConta);
        $valor = $this->service->aplicarTaxa(10, 'P');

        $novoSaldo = round($conta['saldo'] - $valor, 2);
        $this->service->atualizarSaldo($this->numeroConta, $novoSaldo);
        $contaAtualizada = $this->service->buscarConta($this->numeroConta);

        $this->assertEquals($novoSaldo, $contaAtualizada['saldo']);
    }
}