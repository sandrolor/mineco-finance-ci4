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

    /**
     * Retorna os movimentos com dados adicionais para listagem.
     * Inclui o nome da conta e da categoria relacionados.
     */
    public function getMovimento($search = null)
    {

        $builder = $this->db->table('movimento')
            ->select('movimento.*, contas.nomeconta AS nome_conta, categorias.nomecategoria AS nome_categoria')
            ->join('contas', 'contas.id = movimento.conta_id')
            ->join('categorias', 'categorias.id = movimento.categoria_id');

        if (!empty($search)) {
            $builder->like('movimento.historico', $search)
                ->orLike('contas.nome', $search)
                ->orLike('categorias.nome', $search);
        }

        // Ordenar por data de movimento decrescente
        $builder->orderBy('data_mov', 'DESC');
        return $builder;
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
                'data_mov' => $dados['data_mov'],
                'historico'      => $dados['historico'],
                'conta_id'       => $dados['conta_id'], // Origem
                'tipo'           => 'Transferência',
                'categoria_id'   => $dados['categoria_id'], // Categoria padrão: "Transferência"
                'valor'          => -abs($dados['valor']), // Negativo
            ]);

            // Movimento da conta de destino (valor positivo)
            $this->insert([
                'data_mov' => $dados['data_mov'],
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

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
