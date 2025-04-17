<?php

namespace App\Controllers;

use App\Models\MovimentoModel;

class Relatorios extends BaseController
{
    protected $movimentoModel;

    public function __construct()
    {
        $this->movimentoModel = new MovimentoModel();
    }

    // Relatório de saldo das contas
    public function saldoContas()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Define o período padrão como o mês atual, se não houver filtros de data
        if (empty($startDate) && empty($endDate)) {
            $startDate = date('Y-m-01'); // Primeiro dia do mês atual
            $endDate = date('Y-m-t');   // Último dia do mês atual
        }

        // Obter saldos atuais
        $saldosAtuais = $this->movimentoModel->getSaldoContas($startDate, $endDate);

        // Obter saldos anteriores
        $saldosAnteriores = $this->movimentoModel->getSaldoAnteriorContas($startDate);
        $saldosAnterioresMap = [];
        foreach ($saldosAnteriores as $saldo) {
            $saldosAnterioresMap[$saldo['conta_id']] = $saldo['saldo_anterior'] ?? 0;
        }

        // Combinar saldos atuais e anteriores
        foreach ($saldosAtuais as &$saldo) {
            $contaId = $saldo['conta_id'];
            $saldo['saldo_anterior'] = $saldosAnterioresMap[$contaId] ?? 0;
            $saldo['saldo_atual'] = ($saldo['saldo_anterior'] ?? 0) + ($saldo['saldo'] ?? 0); // Calcula o Saldo Atual
        }

        $data['saldos'] = $saldosAtuais;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return view('relatorios/saldo_contas', $data);
    }

    // Relatório de resultado por categorias
    public function resultadoCategorias()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Define o período padrão como o mês atual, se não houver filtros de data
        if (empty($startDate) && empty($endDate)) {
            $startDate = date('Y-m-01'); // Primeiro dia do mês atual
            $endDate = date('Y-m-t');   // Último dia do mês atual
        }

        $data['resultados'] = $this->movimentoModel->getResultadoCategorias($startDate, $endDate);
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return view('relatorios/resultado_categorias', $data);
    }
}
