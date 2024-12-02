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
}