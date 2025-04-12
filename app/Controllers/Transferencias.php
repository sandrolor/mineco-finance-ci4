<?php

namespace App\Controllers;

use App\Models\TransferenciasModel;
use App\Models\ContasModel;

class Transferencias extends BaseController
{
    protected $transferenciasModel;
    protected $contasModel;

    public function __construct()
    {
        $this->transferenciasModel = new TransferenciasModel();
        $this->contasModel = new ContasModel();
    }

    // Listagem de transferências
    public function index()
    {
        // Dados para exibição (ajustar conforme necessário)
        // $data['transferencias'] = []; // Implementar lógica de listagem se necessário
        // return view('transferencias/index', $data);

        // Obter movimentos de saída (transferências)
        $transferencias = $this->transferenciasModel
            ->select('movimento.id, movimento.data_mov, movimento.historico, movimento.valor, contas_origem.nomeconta as conta_origem, contas_destino.nomeconta as conta_destino')
            ->join('contas as contas_origem', 'movimento.conta_id = contas_origem.id') // Conta origem
            ->join('contas as contas_destino', 'movimento.conta_destino_id = contas_destino.id') // Conta destino
            ->where('movimento.categoria_id', function ($builder) {
                // Buscar ID da categoria "Transferência"
                $builder->select('id')->from('categorias')->where('nomecategoria', 'Transferência')->limit(1);
            })
            ->where('movimento.valor <', 0) // Apenas saídas
            ->where('movimento.user_id', session()->get('user_id'))
            ->paginate(10);

        // Dados para exibição
        $data = [
            'transferencias' => $transferencias,
            'pager' => $this->transferenciasModel->pager, // Paginação
        ];

        return view('transferencias/index', $data);
    }

    // Formulário de nova transferência
    public function create()
    {
        $userId = session()->get('user_id');
        $data['contas'] = $this->contasModel->where('user_id', $userId)->findAll();
        return view('transferencias/form', $data);
    }

    // Armazenar transferência
    public function store()
    {
        $data = $this->request->getPost();
        $status = $this->transferenciasModel->registrarTransferencia($data);

        if ($status) {
            return redirect()->to('transferencias')->with('success', 'Transferência realizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao realizar transferência.');
    }

    public function edit($id)
    {
        $userId = session()->get('user_id');
        $data['contas'] = $this->contasModel->where('user_id', $userId)->findAll();
        $data['transferencia'] = $this->transferenciasModel->find($id); // Dados da transferência
        $transferencia = $this->transferenciasModel
            ->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$transferencia) {
            return redirect()->to('/transferencias')->with('error', 'Transferência não encontrada ou acesso negado.');
        }
        return view('transferencias/form', $data); // Reutilizamos o mesmo formulário
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        $transferencia = $this->transferenciasModel
            ->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$transferencia) {
            return redirect()->to('/transferencias')->with('error', 'Transferência não encontrada ou acesso negado.');
        }

        // Atualizar os dois movimentos (origem e destino)
        $status = $this->transferenciasModel->updateTransferencia($id, $data);

        if ($status) {
            return redirect()->to('transferencias')->with('success', 'Transferência atualizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao atualizar transferência.');
    }

    public function delete($id)
    {
        $status = $this->transferenciasModel->deleteTransferencia($id);
        $transferencia = $this->transferenciasModel
            ->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$transferencia) {
            return redirect()->to('/transferencias')->with('error', 'Transferência não encontrada ou acesso negado.');
        }
        if ($status) {
            return redirect()->to('transferencias')->with('success', 'Transferência excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao excluir transferência.');
    }
}
