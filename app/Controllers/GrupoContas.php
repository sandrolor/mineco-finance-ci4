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

        // Obtém os dados do formulário
        $data = $this->request->getPost();
        $data['user_id'] = session()->get('user_id'); // Adiciona o user_id à lista de dados

        // Verifica se já existe um grupo com o mesmo nome para o mesmo user_id
        $existingGroup = $grupoContasModel
            ->where('nomegrupo', $data['nomegrupo'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingGroup) {
            return redirect()->back()
                ->with('errors', ['nomegrupo' => 'O nome do grupo já está em uso.'])
                ->withInput();
        }

        // Salva os dados no banco de dados
        $grupoContasModel->save([
            'nomegrupo' => $data['nomegrupo'],
            'user_id' => $data['user_id']
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
        // Obtém os dados do formulário
        $data = $this->request->getPost();
        $data['user_id'] = session()->get('user_id'); // Adiciona o user_id à lista de dados

        // Verifica se já existe um grupo com o mesmo nome para o mesmo user_id
        $existingGroup = $grupoContasModel
            ->where('nomegrupo', $data['nomegrupo'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingGroup) {
            return redirect()->back()
                ->with('errors', ['nomegrupo' => 'O nome do grupo já está em uso.'])
                ->withInput();
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
