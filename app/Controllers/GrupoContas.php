<?php

namespace App\Controllers;

use App\Models\GrupoContasModel;

class GrupoContas extends BaseController
{
    public function index()
    {
        $grupoContasModel = new GrupoContasModel();

        $search = $this->request->getGet('search');
        $grupoContasModel->where('user_id', session()->get('user_id')); // 👈 filtro por usuário

        if ($search) {
            $grupoContasModel->like('nomegrupo', $search);
        }

        $data = [
            'grupos' => $grupoContasModel->orderBy('nomegrupo', 'ASC')->paginate(5),
            'pager' => $grupoContasModel->pager->setPath('grupocontas'),
            'search' => $search,
        ];

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
        $grupoContasModel->save([
            'nomegrupo' => $this->request->getPost('nomegrupo'),
            'user_id' => session()->get('user_id') // 👈 vincula ao usuário logado
        ]);

        return redirect()->to('/grupocontas')->with('success', 'Grupo contas criado com sucesso!');
    }

    public function edit($id)
    {
        $grupoContasModel = new GrupoContasModel();
        $grupo = $grupoContasModel->where('id', $id)
            ->where('user_id', session()->get('user_id')) // 👈 protege acesso
            ->first();

        if (!$grupo) {
            return redirect()->to('/grupocontas')->with('error', 'Grupo não encontrado ou acesso negado.');
        }

        return view('grupocontas/edit', ['grupo' => $grupo]);
    }

    public function update($id)
    {
        $grupoContasModel = new GrupoContasModel();
        $grupo = $grupoContasModel->where('id', $id)
            ->where('user_id', session()->get('user_id')) // 👈 protege acesso
            ->first();

        if (!$grupo) {
            return redirect()->to('/grupocontas')->with('error', 'Grupo não encontrado ou acesso negado.');
        }

        $grupoContasModel->update($id, [
            'nomegrupo' => $this->request->getPost('nomegrupo'),
        ]);

        return redirect()->to('/grupocontas')->with('success', 'Grupo contas atualizado com sucesso!');
    }

    public function delete($id)
    {
        $grupoContasModel = new GrupoContasModel();
        $grupo = $grupoContasModel->where('id', $id)
            ->where('user_id', session()->get('user_id')) // 👈 protege acesso
            ->first();

        if (!$grupo) {
            return redirect()->to('/grupocontas')->with('error', 'Grupo não encontrado ou acesso negado.');
        }

        $grupoContasModel->delete($id);

        return redirect()->to('/grupocontas')->with('success', 'Grupo contas excluído com sucesso!');
    }
}
