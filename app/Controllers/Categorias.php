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
            'categorias' => $this->categoriasModel->getAllWithGroup($search),
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
        $this->categoriasModel->save($this->request->getPost());
        return redirect()->to('categorias')->with('success', 'Categoria adicionada com sucesso!');
    }

    public function edit($id)
    {
        $data = [
            'categoria' => $this->categoriasModel->find($id),
            'grupos' => $this->categoriasModel->getAllGroups(),
        ];

        return view('categorias/edit', $data);
    }

    public function update($id)
    {
        $this->categoriasModel->update($id, $this->request->getPost());
        return redirect()->to('categorias')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function delete($id)
    {
        $this->categoriasModel->delete($id);
        return redirect()->to('categorias')->with('success', 'Categoria excluída com sucesso!');
    }
}