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
            'contas' => $this->contasModel->getAllWithGroup($search, session()->get('user_id')),
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
        // Obtém os dados do formulário
        $data = $this->request->getPost();
        $data['user_id'] = session()->get('user_id'); // Adiciona o user_id à lista de dados

        // Verifica se já existe um grupo com o mesmo nome para o mesmo user_id
        $existingGroup = $this->contasModel
            ->where('nomeconta', $data['nomeconta'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingGroup) {
            return redirect()->back()
                ->with('errors', ['nomeconta' => 'O nome da conta já está em uso.'])
                ->withInput();
        }

        // Salva os dados no banco de dados
        $post = $this->request->getPost();
        $post['user_id'] = session()->get('user_id');
        $this->contasModel->save($post);

        return redirect()->to('contas')->with('success', 'Conta adicionada com sucesso!');
    }

    public function edit($id)
    {
        $conta = $this->contasModel->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$conta) {
            return redirect()->to('contas')->with('error', 'Conta não encontrada ou acesso negado.');
        }

        if (($conta['nomeconta']) === 'Transferência') {
            return redirect()->back()
                ->with('errors', ['nomeconta' => 'Registro não permite esta função.'])
                ->withInput();
        }

        $data = [
            'conta' => $conta,
            'grupos' => $this->contasModel->getAllGroups(),
        ];

        return view('contas/edit', $data);
    }

    public function update($id)
    {
        $conta = $this->contasModel->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$conta) {
            return redirect()->to('contas')->with('error', 'Conta não encontrada ou acesso negado.');
        }

        // Obtém os dados do formulário
        $data = $this->request->getPost();
        $data['user_id'] = session()->get('user_id'); // Adiciona o user_id à lista de dados

        // Verifica se já existe um grupo com o mesmo nome para o mesmo user_id
        $existingGroup = $this->contasModel
            ->where('nomeconta', $data['nomeconta'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($existingGroup) {
            return redirect()->back()
                ->with('errors', ['nomeconta' => 'O nome da conta já está em uso.'])
                ->withInput();
        }

        $this->contasModel->update($id, $this->request->getPost());
        return redirect()->to('contas')->with('success', 'Conta atualizada com sucesso!');
    }

    public function delete($id)
    {
        $conta = $this->contasModel->where('id', $id)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$conta) {
            return redirect()->to('contas')->with('error', 'Conta não encontrada ou acesso negado.');
        }

        if (($conta['nomeconta']) === 'Transferência') {
            return redirect()->back()
                ->with('errors', ['nomeconta' => 'Registro não permite esta função.'])
                ->withInput();
        }

        $this->contasModel->delete($id);
        return redirect()->to('contas')->with('success', 'Conta excluída com sucesso!');
    }
}
