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

    // Listar movimentos com paginação e busca
    public function index()
    {
        $dataInicial = $this->request->getGet('data_inicial') ?? date('Y-m-01');
        $dataFinal = $this->request->getGet('data_final') ?? date('Y-m-d');
        $contaId = $this->request->getGet('conta_id');
        $categoriaId = $this->request->getGet('categoria_id');

        $movimentos = $this->movimentoModel->filtrarMovimentos($dataInicial, $dataFinal, $contaId, $categoriaId);

        $contas = $this->contaModel->findAll();
        $categorias = $this->categoriaModel->findAll();

        return view('movimento/index', [
            'movimentos' => $movimentos,
            'dataInicial' => $dataInicial,
            'dataFinal' => $dataFinal,
            'contas' => $contas,
            'categorias' => $categorias,
            'contaSelecionada' => $contaId,
            'categoriaSelecionada' => $categoriaId,
        ]);
    }

    // Formulário para criar um novo movimento
    public function create()
    {
        $data = [
            'contas' => $this->contaModel->findAll(),
            'categorias' => $this->categoriaModel->findAll(),
        ];

        return view('movimento/create', $data);
    }

    // Salvar novo movimento
    public function store()
    {
        $dados = $this->request->getPost();

        // Validar entrada
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

        // Tratar transferências
        if ($dados['tipo'] === 'Transferência') {
            if (empty($dados['conta_destino_id']) || $dados['conta_id'] === $dados['conta_destino_id']) {
                return redirect()->back()->with('errors', ['conta_destino_id' => 'Selecione uma conta de destino válida.'])->withInput();
            }
            $dados['categoria_id'] = $this->categoriaModel->where('nome', 'Transferência')->first()['id'] ?? null;
        }

        // Salvar movimento
        $this->movimentoModel->inserirMovimento($dados);
        return redirect()->to('/movimento')->with('success', 'Movimento registrado com sucesso.');
    }

    // Formulário para editar movimento
    public function edit($id)
    {
        $movimento = $this->movimentoModel->find($id);

        if (!$movimento) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Movimento não encontrado.");
        }

        $data = [
            'movimento' => $movimento,
            'contas' => $this->contaModel->findAll(),
            'categorias' => $this->categoriaModel->findAll(),
        ];

        return view('movimento/edit', $data);
    }

    // Atualizar movimento
    public function update($id)
    {
        $dados = $this->request->getPost();

        // Validar entrada
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

        // Tratar transferências
        if ($dados['tipo'] === 'Transferência') {
            if (empty($dados['conta_destino_id']) || $dados['conta_id'] === $dados['conta_destino_id']) {
                return redirect()->back()->with('errors', ['conta_destino_id' => 'Selecione uma conta de destino válida.'])->withInput();
            }
            $dados['categoria_id'] = $this->categoriaModel->where('nome', 'Transferência')->first()['id'] ?? null;
        }

        // Para Receita ou Despesa
        $dados['valor'] = ($dados['tipo'] === 'Despesa') ? -abs($dados['valor']) : abs($dados['valor']);

        // Atualizar movimento
        $this->movimentoModel->update($id, $dados);
        return redirect()->to('/movimento')->with('success', 'Movimento atualizado com sucesso.');
    }

    // Excluir movimento
    public function delete($id)
    {
        $this->movimentoModel->delete($id);
        return redirect()->to('/movimento')->with('success', 'Movimento excluído com sucesso.');
    }
}
