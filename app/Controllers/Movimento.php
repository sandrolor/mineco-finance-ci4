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
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $contaId = $this->request->getGet('conta_id');
        $categoriaId = $this->request->getGet('categoria_id');

        // Define o período padrão como o mês atual, se não houver filtros de data
        if (empty($startDate) && empty($endDate)) {
            $startDate = date('Y-m-01'); // Primeiro dia do mês atual
            $endDate = date('Y-m-t');   // Último dia do mês atual
        }

        // Calcula o saldo anterior acumulado até o início do período
        $saldoAnterior = $this->movimentoModel->where('user_id', session()->get('user_id'))
            ->where('data_mov <', $startDate)
            ->selectSum('valor')
            ->first()['valor'] ?? 0;

        // Busca os movimentos dentro do período especificado
        $query = $this->movimentoModel->getMovimento($search, session()->get('user_id'))
            ->where('data_mov >=', $startDate)
            ->where('data_mov <=', $endDate);

        // Aplica filtros adicionais
        if (!empty($contaId)) {
            $query->where('conta_id', $contaId);
        }
        if (!empty($categoriaId)) {
            $query->where('categoria_id', $categoriaId);
        }

        $movimentos = $query->orderBy('data_mov', 'ASC')->get()->getResultArray();
        $total = $query->countAllResults(false);

        // Carrega dados das contas e categorias para os selects dinâmicos
        $data = [
            'movimentos' => $movimentos,
            'total' => $total,
            'saldo_anterior' => $saldoAnterior,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'search' => $search,
            'contas' => $this->contaModel->where('user_id', session()->get('user_id'))->orderBy('nomeconta', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->where('user_id', session()->get('user_id'))->orderBy('nomecategoria', 'ASC')->findAll(),
        ];

        return view('movimento/index', $data);
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $data = [
            'contas' => $this->contaModel->where('user_id', $userId)->orderBy('nomeconta', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->where('user_id', $userId)
                ->where('nomecategoria !=', 'Transferência') // Remove transferência
                ->orderBy('nomecategoria', 'ASC')
                ->findAll(),
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
            $dados['categoria_id'] = $this->categoriaModel->where('nomecategoria', 'Transferência')->first()['id'] ?? null;
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
            'contas' => $this->contaModel->where('user_id', $userId)->orderBy('nomeconta', 'ASC')->findAll(),
            'categorias' => $this->categoriaModel->where('user_id', $userId)
                ->where('nomecategoria !=', 'Transferência') // Remove transferência
                ->orderBy('nomecategoria', 'ASC')
                ->findAll(),
        ];

        if (($data['movimento']['tipo'] ?? null) === null) {
            return redirect()->back()
                ->with('errors', ['tipo' => 'Utilize a rotina de Transferência.'])
                ->withInput();
        }

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
            $dados['categoria_id'] = $this->categoriaModel->where('nomecategoria', 'Transferência')->first()['id'] ?? null;
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
        
        if (($movimento['tipo'] ?? null) === null) {
            return redirect()->back()
                ->with('errors', ['tipo' => 'Utilize a rotina de Transferência.'])
                ->withInput();
        }

        $this->movimentoModel->delete($id);
        return redirect()->to('/movimento')->with('success', 'Movimento excluído com sucesso.');
    }
}
