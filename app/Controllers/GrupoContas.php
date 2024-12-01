<?php

namespace App\Controllers;

use App\Models\GrupoContasModel;

class GrupoContas extends BaseController
{
    public function index()
    {
        $grupoContasModel = new GrupoContasModel();

        $search = $this->request->getGet('search'); // Obtém o valor do campo de busca

        if ($search) {
            $grupoContasModel->like('nomegrupo', $search); // Adiciona a condição de busca
        }

        $data = [
            'grupos' => $grupoContasModel->orderBy('nomegrupo', 'ASC')->paginate(5), // 5 registros por página
            'pager' => $grupoContasModel->pager->setPath('grupocontas'), // Objeto do Pager
            'search' => $search, // Passa o termo de busca para a view
        ];

        // Total de registros
        $data['total'] = $grupoContasModel->countAllResults(false);

        return view('grupocontas/index', $data);
    }

    public function create()
    {
        return view('grupocontas/create');
    }

    public function store()
    {
        $grupoContasModel = new GrupoContasModel();
        $grupoContasModel->save(['nomegrupo' => $this->request->getPost('nomegrupo')]);

        return redirect()->to('/grupocontas')->with('success', 'Grupo contas criado com sucesso!');
    }

    public function edit($id)
    {
        $grupoContasModel = new GrupoContasModel();
        $data['grupo'] = $grupoContasModel->find($id);

        return view('grupocontas/edit', $data);
    }

    public function update($id)
    {
        $grupoContasModel = new GrupoContasModel();
        $grupoContasModel->update($id, ['nomegrupo' => $this->request->getPost('nomegrupo')]);

        return redirect()->to('/grupocontas')->with('success', 'Grupo contas atualizado com sucesso!');
    }

    public function delete($id)
    {
        $grupoContasModel = new GrupoContasModel();
        $grupoContasModel->delete($id);

        return redirect()->to('/grupocontas')->with('success', 'Grupo contas excluído com sucesso!');
    }
}