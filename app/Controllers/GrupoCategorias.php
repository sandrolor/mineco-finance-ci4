<?php

namespace App\Controllers;

use App\Models\GrupoCategoriasModel;

class GrupoCategorias extends BaseController
{
    public function index()
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();

        $search = $this->request->getGet('search'); // Obtém o valor do campo de busca

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
        $grupoCategoriasModel->save(['nomegrupo' => $this->request->getPost('nomegrupo')]);

        return redirect()->to('/grupocategorias')->with('success', 'Grupo categorias criado com sucesso!');
    }

    public function edit($id)
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();
        $data['grupo'] = $grupoCategoriasModel->find($id);

        return view('grupocategorias/edit', $data);
    }

    public function update($id)
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();
        $grupoCategoriasModel->update($id, ['nomegrupo' => $this->request->getPost('nomegrupo')]);

        return redirect()->to('/grupocategorias')->with('success', 'Grupo categorias atualizado com sucesso!');
    }

    public function delete($id)
    {
        $grupoCategoriasModel = new GrupoCategoriasModel();
        $grupoCategoriasModel->delete($id);

        return redirect()->to('/grupocategorias')->with('success', 'Grupo categorias excluído com sucesso!');
    }
}