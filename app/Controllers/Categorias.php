<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriasModel;

class Categorias extends BaseController
{
    protected $categoriasModel;

    public function __construct()
    {
        $this->categoriasModel = new CategoriasModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $data = [
            'categorias' => $this->categoriasModel->getAllWithGroup($search, session()->get('user_id')),
            'pager' => $this->categoriasModel->pager,
            'search' => $search,
        ];

        return view('categorias/index', $data);
    }

    public function create()
    {
        $data = [
            'grupos' => $this->categoriasModel->getAllGroups(),
        ];

        return view('categorias/create', $data);
    }

    public function store()
    {
        $post = $this->request->getPost();
        $post['user_id'] = session()->get('user_id');
        $this->categoriasModel->save($post);
        
        return redirect()->to('categorias')->with('success', 'Categoria adicionada com sucesso!');
    }

    public function edit($id)
    {
        $categoria = $this->categoriasModel->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$categoria) {
            return redirect()->to('contas')->with('error', 'Conta não encontrada ou acesso negado.');
        }

        $data = [
            'categoria' => $categoria,
            'grupos' => $this->categoriasModel->getAllGroups(),
        ];

        return view('categorias/edit', $data);
    }

    public function update($id)
    {
        $categoria = $this->categoriasModel->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$categoria) {
            return redirect()->to('contas')->with('error', 'Conta não encontrada ou acesso negado.');
        }
        $this->categoriasModel->update($id, $this->request->getPost());
        return redirect()->to('categorias')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function delete($id)
    {
        $categoria = $this->categoriasModel->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$categoria) {
            return redirect()->to('contas')->with('error', 'Conta não encontrada ou acesso negado.');
        }
        $this->categoriasModel->delete($id);
        return redirect()->to('categorias')->with('success', 'Categoria excluída com sucesso!');
    }
}
