<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimentoModel extends Model
{
    protected $table            = 'movimento';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'data_mov',
        'historico',
        'conta_id',
        'tipo',
        'categoria_id',
        'conta_destino_id',
        'valor'
    ];

    public function filtrarMovimentos($dataInicial, $dataFinal, $contaId = null, $categoriaId = null)
    {
        $builder = $this->db->table($this->table)
            ->select('movimento.*, contas.nomeconta AS nomeconta, categorias.nomecategoria AS nomecategoria')
            ->join('contas', 'contas.id = movimento.conta_id', 'left')
            ->join('categorias', 'categorias.id = movimento.categoria_id', 'left')
            ->where('data_mov >=', $dataInicial)
            ->where('data_mov <=', $dataFinal);

        if ($contaId) {
            $builder->where('conta_id', $contaId);
        }

        if ($categoriaId) {
            $builder->where('categoria_id', $categoriaId);
        }
        $builder->orderBy('movimento.data_mov', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getSaldoContas($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('movimento')
            ->select('contas.nomeconta AS nome_conta, SUM(movimento.valor) AS saldo')
            ->join('contas', 'contas.id = movimento.conta_id')
            ->groupBy('movimento.conta_id');

        // Aplicar filtro de data, se fornecido
        if ($startDate) {
            $builder->where('movimento.data_mov >=', $startDate);
        }
        if ($endDate) {
            $builder->where('movimento.data_mov <=', $endDate);
        }

        return $builder->get()->getResultArray();
    }

    public function getResultadoCategorias($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('movimento')
            ->select('categorias.nomecategoria AS nome_categoria')
            ->select('SUM(CASE WHEN movimento.valor > 0 THEN movimento.valor ELSE 0 END) AS receitas')
            ->select('SUM(CASE WHEN movimento.valor < 0 THEN movimento.valor ELSE 0 END) AS despesas')
            ->select('SUM(movimento.valor) AS saldo')
            ->join('categorias', 'categorias.id = movimento.categoria_id')
            ->groupBy('movimento.categoria_id');

        // Aplicar filtro de data, se fornecido
        if ($startDate) {
            $builder->where('movimento.data_mov >=', $startDate);
        }
        if ($endDate) {
            $builder->where('movimento.data_mov <=', $endDate);
        }

        return $builder->get()->getResultArray();
    }

    public function getSaldoAnterior($dataInicial, $contaId = null, $categoriaId = null)
    {
        $builder = $this->db->table('movimento');
        $builder->selectSum('valor', 'saldo_anterior');
        $builder->where('data_mov <', $dataInicial);

        if ($contaId) {
            $builder->where('conta_id', $contaId);
        }

        if ($categoriaId) {
            $builder->where('categoria_id', $categoriaId);
        }

        $result = $builder->get()->getRowArray();
        return $result['saldo_anterior'] ?? 0;
    }

    public function getMovimentosFiltrados($filtros = [])
    {
        $builder = $this->db->table('movimento');
        $builder->select('
        movimento.id,
        movimento.data_mov AS data_mov,
        movimento.historico,
        movimento.valor,
        contas.nomeconta AS nomeconta,
        categorias.nomecategoria AS nomecategoria
    ');

        $builder->join('contas', 'movimento.conta_id = contas.id', 'left');
        $builder->join('categorias', 'movimento.categoria_id = categorias.id', 'left');

        if (!empty($filtros['dataInicial'])) {
            $builder->where('movimento.data_mov >=', $filtros['dataInicial']);
        }

        if (!empty($filtros['dataFinal'])) {
            $builder->where('movimento.data_mov <=', $filtros['dataFinal']);
        }

        if (!empty($filtros['contaId'])) {
            $builder->where('movimento.conta_id', $filtros['contaId']);
        }

        if (!empty($filtros['categoriaId'])) {
            $builder->where('movimento.categoria_id', $filtros['categoriaId']);
        }

        $builder->orderBy('movimento.data_mov', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getContas()
    {
        $builder = $this->db->table('contas');
        $builder->select('id, nomeconta');
        $builder->orderBy('nomeconta', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getCategorias()
    {
        $builder = $this->db->table('categorias');
        $builder->select('id, nomecategoria');
        $builder->orderBy('nomecategoria', 'ASC');
        return $builder->get()->getResultArray();
    }

    /**
     * Insere um novo movimento (Receita, Despesa ou Transferência).
     */
    public function inserirMovimento(array $dados)
    {
        // Se for transferência
        if ($dados['tipo'] === 'Transferência') {
            $db = \Config\Database::connect();
            $db->transStart();

            // Movimento da conta de origem (valor negativo)
            $this->insert([
                'data_movimento' => $dados['data_movimento'],
                'historico'      => $dados['historico'],
                'conta_id'       => $dados['conta_id'], // Origem
                'tipo'           => 'Transferência',
                'categoria_id'   => $dados['categoria_id'], // Categoria padrão: "Transferência"
                'valor'          => -abs($dados['valor']), // Negativo
            ]);

            // Movimento da conta de destino (valor positivo)
            $this->insert([
                'data_movimento' => $dados['data_movimento'],
                'historico'      => $dados['historico'],
                'conta_id'       => $dados['conta_destino_id'], // Destino
                'tipo'           => 'Transferência',
                'categoria_id'   => $dados['categoria_id'], // Categoria padrão: "Transferência"
                'valor'          => abs($dados['valor']), // Positivo
            ]);

            $db->transComplete();
            return $db->transStatus();
        }

        // Para Receita ou Despesa
        $dados['valor'] = ($dados['tipo'] === 'Despesa') ? -abs($dados['valor']) : abs($dados['valor']);
        return $this->insert($dados);
    }
}
