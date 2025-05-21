<?php
class Conta {
    public $numero_conta;
    public $saldo;

    public function __construct($numero_conta, $saldo) {
        $this->numero_conta = $numero_conta;
        $this->saldo = $saldo;
    }
}