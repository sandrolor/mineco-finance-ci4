<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContasModel;

class Contas extends BaseController
{
    protected $contasModel;

    public function __construct()
    {
        $this->contasModel = new ContasModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $data = [
            'contas' => $this->contasModel->getAllWithGroup($search),
            'pager' => $this->contasModel->pager,
            'search' => $search,
        ];

        return view('contas/index', $data);
    }

    public function create()
    {
        $data = [
            'grupos' => $this->contasModel->getAllGroups(),
        ];

        return view('contas/create', $data);
    }

    public function store()
    {
        $this->contasModel->save($this->request->getPost());
        return redirect()->to('contas')->with('success', 'Conta adicionada com sucesso!');
    }

    public function edit($id)
    {
        $data = [
            'conta' => $this->contasModel->find($id),
            'grupos' => $this->contasModel->getAllGroups(),
        ];

        return view('contas/edit', $data);
    }

    public function update($id)
    {
        $this->contasModel->update($id, $this->request->getPost());
        return redirect()->to('contas')->with('success', 'Conta atualizada com sucesso!');
    }

    public function delete($id)
    {
        $this->contasModel->delete($id);
        return redirect()->to('contas')->with('success', 'Conta excluída com sucesso!');
    }
}