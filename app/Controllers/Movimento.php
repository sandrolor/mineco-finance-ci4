<?php

namespace App\Controllers;

use App\Models\MovimentoModel;
use App\Models\ContasModel;
use App\Models\CategoriasModel;

class Movimento extends BaseController
{
    protected $movimentoModel;
    protected $contaModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->movimentoModel = new MovimentoModel();
        $this->contaModel = new ContasModel();
        $this->categoriaModel = new CategoriasModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $query = $this->movimentoModel->getMovimento($search, session()->get('user_id'));

        $perPage = 10;
        $currentPage = (int)($this->request->getGet('page') ?? 1);
        $offset = ($currentPage - 1) * $perPage;

        $movimentos = $query->orderBy('data_mov', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        $total = $query->countAllResults(false);

        return view('movimento/index', [
            'movimentos' => $movimentos,
            'total' => $total,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'search' => $search
        ]);
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $data = [
            'contas' => $this->contaModel->where('user_id', $userId)->findAll(),
            'categorias' => $this->categoriaModel->where('user_id', $userId)->findAll(),
        ];

        return view('movimento/create', $data);
    }

    public function store()
    {
        $dados = $this->request->getPost();
        $dados['user_id'] = session()->get('user_id');

        if (!$this->validate([
            'data_mov' => 'required|valid_date',
            'historico' => 'required|max_length[255]',
            'conta_id' => 'required|is_not_unique[contas.id]',
            'valor' => 'required|decimal',
            'tipo' => 'required|in_list[Receita,Despesa,Transferência]',
            'categoria_id' => 'permit_empty|is_not_unique[categorias.id]',
            'conta_destino_id' => 'permit_empty|is_not_unique[contas.id]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        if ($dados['tipo'] === 'Transferência') {
            if (empty($dados['conta_destino_id']) || $dados['conta_id'] === $dados['conta_destino_id']) {
                return redirect()->back()->with('errors', ['conta_destino_id' => 'Selecione uma conta de destino válida.'])->withInput();
            }
            $dados['categoria_id'] = $this->categoriaModel->where('nome', 'Transferência')->first()['id'] ?? null;
        }

        $this->movimentoModel->inserirMovimento($dados);
        return redirect()->to('/movimento')->with('success', 'Movimento registrado com sucesso.');
    }

    public function edit($id)
    {
        $userId = session()->get('user_id');
        $movimento = $this->movimentoModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$movimento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Movimento não encontrado.");
        }

        $data = [
            'movimento' => $movimento,
            'contas' => $this->contaModel->where('user_id', $userId)->findAll(),
            'categorias' => $this->categoriaModel->where('user_id', $userId)->findAll(),
        ];

        return view('movimento/edit', $data);
    }

    public function update($id)
    {
        $userId = session()->get('user_id');
        $movimento = $this->movimentoModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$movimento) {
            return redirect()->to('/movimento')->with('error', 'Movimento não encontrado ou acesso negado.');
        }

        $dados = $this->request->getPost();

        if (!$this->validate([
            'data_mov' => 'required|valid_date',
            'historico' => 'required|max_length[255]',
            'conta_id' => 'required|is_not_unique[contas.id]',
            'valor' => 'required|decimal',
            'tipo' => 'required|in_list[Receita,Despesa,Transferência]',
            'categoria_id' => 'permit_empty|is_not_unique[categorias.id]',
            'conta_destino_id' => 'permit_empty|is_not_unique[contas.id]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        if ($dados['tipo'] === 'Transferência') {
            if (empty($dados['conta_destino_id']) || $dados['conta_id'] === $dados['conta_destino_id']) {
                return redirect()->back()->with('errors', ['conta_destino_id' => 'Selecione uma conta de destino válida.'])->withInput();
            }
            $dados['categoria_id'] = $this->categoriaModel->where('nome', 'Transferência')->first()['id'] ?? null;
        }

        $dados['valor'] = ($dados['tipo'] === 'Despesa') ? -abs($dados['valor']) : abs($dados['valor']);
        $this->movimentoModel->update($id, $dados);
        return redirect()->to('/movimento')->with('success', 'Movimento atualizado com sucesso!');
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $movimento = $this->movimentoModel->where('id', $id)->where('user_id', $userId)->first();

        if (!$movimento) {
            return redirect()->to('/movimento')->with('error', 'Movimento não encontrado ou acesso negado.');
        }

        $this->movimentoModel->delete($id);
        return redirect()->to('/movimento')->with('success', 'Movimento excluído com sucesso.');
    }
}
