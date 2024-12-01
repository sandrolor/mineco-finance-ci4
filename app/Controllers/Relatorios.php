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

        $data['saldos'] = $this->movimentoModel->getSaldoContas($startDate, $endDate);
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return view('relatorios/saldo_contas', $data);
    }

    // Relatório de resultado por categorias
    public function resultadoCategorias()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $data['resultados'] = $this->movimentoModel->getResultadoCategorias($startDate, $endDate);
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return view('relatorios/resultado_categorias', $data);
    }
}