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
        'valor',
        'user_id' // 👈 necessário para multiusuário
    ];

    public function getMovimento($search = null, $userId = null)
    {
        $builder = $this->db->table('movimento')
            ->select('movimento.*, contas.nomeconta AS nome_conta, categorias.nomecategoria AS nome_categoria')
            ->join('contas', 'contas.id = movimento.conta_id')
            ->join('categorias', 'categorias.id = movimento.categoria_id')
            ->where('movimento.user_id', $userId); // 👈 filtro multiusuário

        if (!empty($search)) {
            $builder->like('movimento.historico', $search)
                ->orLike('contas.nomeconta', $search)
                ->orLike('categorias.nomecategoria', $search);
        }

        $builder->orderBy('data_mov', 'DESC');
        return $builder;
    }

    public function inserirMovimento(array $dados)
    {
        $userId = $dados['user_id'] ?? session()->get('user_id');

        if ($dados['tipo'] === 'Transferência') {
            $db = \Config\Database::connect();
            $db->transStart();

            $this->insert([
                'data_mov' => $dados['data_mov'],
                'historico' => $dados['historico'],
                'conta_id' => $dados['conta_id'],
                'tipo' => 'Transferência',
                'categoria_id' => $dados['categoria_id'],
                'valor' => -abs($dados['valor']),
                'user_id' => $userId
            ]);

            $this->insert([
                'data_mov' => $dados['data_mov'],
                'historico' => $dados['historico'],
                'conta_id' => $dados['conta_destino_id'],
                'tipo' => 'Transferência',
                'categoria_id' => $dados['categoria_id'],
                'valor' => abs($dados['valor']),
                'user_id' => $userId
            ]);

            $db->transComplete();
            return $db->transStatus();
        }

        $dados['valor'] = ($dados['tipo'] === 'Despesa') ? -abs($dados['valor']) : abs($dados['valor']);
        $dados['user_id'] = $userId;

        return $this->insert($dados);
    }

    public function getSaldoContas($startDate = null, $endDate = null)
    {
        $builder = $this->db->table('movimento')
            ->select('contas.nomeconta AS nome_conta, SUM(movimento.valor) AS saldo')
            ->join('contas', 'contas.id = movimento.conta_id')
            ->where('movimento.user_id', session()->get('user_id')) // 👈 multiusuário
            ->groupBy('movimento.conta_id');

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
            ->where('movimento.user_id', session()->get('user_id')) // 👈 multiusuário
            ->groupBy('movimento.categoria_id');

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

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
}
