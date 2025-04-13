<?php

namespace App\Controllers;

use App\Models\GrupoCategoriasModel;

class GrupoCategorias extends BaseController
{
    public function index()
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();

        $search = $this->request->getGet('search'); // Obtém o valor do campo de busca
        $grupoCategoriasModel->where('user_id', session()->get('user_id')); // 👈 filtro por usuário

        if ($search) {
            $grupoCategoriasModel->like('nomegrupo', $search); // Adiciona a condição de busca
        }

        $data = [
            'grupos' => $grupoCategoriasModel->orderBy('nomegrupo', 'ASC')->paginate(5), // 5 registros por página
            'pager' => $grupoCategoriasModel->pager->setPath('grupocategorias'), // Objeto do Pager
            'search' => $search, // Passa o termo de busca para a view
        ];

        // Total de registros
        $data['total'] = $grupoCategoriasModel->countAllResults(false);

        return view('grupocategorias/index', $data);
    }

    public function create()
    {
        return view('grupocategorias/create');
    }


    public function store()
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();

        // Obtém os dados do formulário
        $data = $this->request->getPost();
        $data['user_id'] = session()->get('user_id'); // Adiciona o user_id à lista de dados

        // Verifica se já existe um grupo com o mesmo nome para o mesmo user_id
        $existingGroup = $grupoCategoriasModel
            ->where('nomegrupo', $data['nomegrupo'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingGroup) {
            return redirect()->back()
                ->with('errors', ['nomegrupo' => 'O nome do grupo já está em uso.'])
                ->withInput();
        }

        // Salva os dados no banco de dados
        $grupoCategoriasModel->save([
            'nomegrupo' => $this->request->getPost('nomegrupo'),
            'user_id' => session()->get('user_id') // 👈 vincula ao usuário logado
        ]);

        return redirect()->to('/grupocategorias')->with('success', 'Grupo categorias criado com sucesso!');
    }

    public function edit($id)
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();
        $grupo = $grupoCategoriasModel->where('id', $id)
            ->where('user_id', session()->get('user_id')) // 👈 protege acesso
            ->first();

        if (!$grupo) {
            return redirect()->to('/grupocategorias')->with('error', 'Grupo não encontrado ou acesso negado.');
        }
        return view('grupocategorias/edit', ['grupo' => $grupo]);
    }

    public function update($id)
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();
        $grupo = $grupoCategoriasModel->where('id', $id)
            ->where('user_id', session()->get('user_id')) // 👈 protege acesso
            ->first();

        if (!$grupo) {
            return redirect()->to('/grupocategorias')->with('error', 'Grupo não encontrado ou acesso negado.');
        }

        // Obtém os dados do formulário
        $data = $this->request->getPost();
        $data['user_id'] = session()->get('user_id'); // Adiciona o user_id à lista de dados

        // Verifica se já existe um grupo com o mesmo nome para o mesmo user_id
        $existingGroup = $grupoCategoriasModel
            ->where('nomegrupo', $data['nomegrupo'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingGroup) {
            return redirect()->back()
                ->with('errors', ['nomegrupo' => 'O nome do grupo já está em uso.'])
                ->withInput();
        }

        $grupoCategoriasModel->update($id, [
            'nomegrupo' => $this->request->getPost('nomegrupo'),
        ]);

        return redirect()->to('/grupocategorias')->with('success', 'Grupo categorias atualizado com sucesso!');
    }

    public function delete($id)
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();
        $grupo = $grupoCategoriasModel->where('id', $id)
            ->where('user_id', session()->get('user_id')) // 👈 protege acesso
            ->first();

        if (!$grupo) {
            return redirect()->to('/grupocategorias')->with('error', 'Grupo não encontrado ou acesso negado.');
        }

        $grupoCategoriasModel->delete($id);

        return redirect()->to('/grupocategorias')->with('success', 'Grupo categorias excluído com sucesso!');
    }
}
