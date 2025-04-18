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

        // Obter saldos atuais com grupos
        $saldosAtuais = $this->movimentoModel->getSaldoContasComGrupos($startDate, $endDate);

        // Obter saldos anteriores por conta
        $saldosAnteriores = $this->movimentoModel->getSaldoAnteriorPorConta($startDate);
        $saldosAnterioresMap = [];
        foreach ($saldosAnteriores as $saldo) {
            $saldosAnterioresMap[$saldo['conta_id']] = $saldo['saldo_anterior'] ?? 0;
        }

        // Organizar os dados por grupo
        $dadosOrganizados = [];
        foreach ($saldosAtuais as $saldo) {
            $grupo = $saldo['nome_grupo'];
            $conta = $saldo['nome_conta'];
            $contaId = $saldo['conta_id'];
            $saldoAtual = $saldo['saldo'];

            // Adicionar subtotal ao grupo
            if (!isset($dadosOrganizados[$grupo])) {
                $dadosOrganizados[$grupo] = [
                    'subtotais' => ['saldo_anterior' => 0, 'movimento' => 0, 'saldo_atual' => 0],
                    'contas' => [],
                ];
            }

            // Adicionar conta ao grupo
            $dadosOrganizados[$grupo]['contas'][] = [
                'nome_conta' => $conta,
                'saldo_anterior' => $saldosAnterioresMap[$contaId] ?? 0,
                'movimento' => $saldoAtual,
                'saldo_atual' => ($saldosAnterioresMap[$contaId] ?? 0) + $saldoAtual,
            ];

            // Atualizar subtotais do grupo
            $dadosOrganizados[$grupo]['subtotais']['saldo_anterior'] += $saldosAnterioresMap[$contaId] ?? 0;
            $dadosOrganizados[$grupo]['subtotais']['movimento'] += $saldoAtual;
            $dadosOrganizados[$grupo]['subtotais']['saldo_atual'] += ($saldosAnterioresMap[$contaId] ?? 0) + $saldoAtual;
        }

        // Calcular totais gerais
        $totalGeral = ['saldo_anterior' => 0, 'movimento' => 0, 'saldo_atual' => 0];
        foreach ($dadosOrganizados as $grupo) {
            $totalGeral['saldo_anterior'] += $grupo['subtotais']['saldo_anterior'];
            $totalGeral['movimento'] += $grupo['subtotais']['movimento'];
            $totalGeral['saldo_atual'] += $grupo['subtotais']['saldo_atual'];
        }

        $data['dados_organizados'] = $dadosOrganizados;
        $data['total_geral'] = $totalGeral;
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

        // Obter resultados com grupos
        $resultadosAtuais = $this->movimentoModel->getResultadoCategoriasComGrupos($startDate, $endDate);

        // Organizar os dados por grupo
        $dadosOrganizados = [];
        foreach ($resultadosAtuais as $resultado) {
            $grupo = $resultado['nome_grupo'];
            $categoria = $resultado['nome_categoria'];
            $receitas = $resultado['receitas'];
            $despesas = $resultado['despesas'];
            $saldo = $resultado['saldo'];

            // Adicionar subtotal ao grupo
            if (!isset($dadosOrganizados[$grupo])) {
                $dadosOrganizados[$grupo] = [
                    'subtotais' => ['receitas' => 0, 'despesas' => 0, 'saldo' => 0],
                    'categorias' => [],
                ];
            }

            // Adicionar categoria ao grupo
            $dadosOrganizados[$grupo]['categorias'][] = [
                'nome_categoria' => $categoria,
                'receitas' => $receitas,
                'despesas' => $despesas,
                'saldo' => $saldo,
            ];

            // Atualizar subtotais do grupo
            $dadosOrganizados[$grupo]['subtotais']['receitas'] += $receitas;
            $dadosOrganizados[$grupo]['subtotais']['despesas'] += $despesas;
            $dadosOrganizados[$grupo]['subtotais']['saldo'] += $saldo;
        }

        // Calcular totais gerais
        $totalGeral = ['receitas' => 0, 'despesas' => 0, 'saldo' => 0];
        foreach ($dadosOrganizados as $grupo) {
            $totalGeral['receitas'] += $grupo['subtotais']['receitas'];
            $totalGeral['despesas'] += $grupo['subtotais']['despesas'];
            $totalGeral['saldo'] += $grupo['subtotais']['saldo'];
        }

        $data['dados_organizados'] = $dadosOrganizados;
        $data['total_geral'] = $totalGeral;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return view('relatorios/resultado_categorias', $data);
    }
}
