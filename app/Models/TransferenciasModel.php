<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferenciasModel extends Model
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
        'user_id'
    ];

    /**
     * Registrar transferência (dois movimentos).
     */
    public function registrarTransferencia($data)
    {

        // ID da categoria "Transferência" (busque do banco ou defina manualmente)
        $categoriaTransferencia = $this->db->table('categorias')
            ->select('id')
            ->where('nomecategoria', 'Transferência')
            ->get()
            ->getRowArray();

        if (!$categoriaTransferencia) {
            throw new \Exception('Categoria "Transferência" não encontrada no banco de dados.');
        }

        $categoriaId = $categoriaTransferencia['id'];

        $this->db->transStart(); // Iniciar transação

        // Movimento de saída (conta origem)
        $userId = session()->get('user_id');
        $saida = [
            'data_mov' => $data['data_mov'],
            'conta_id' => $data['conta_origem'],
            'conta_destino_id' => $data['conta_destino'],
            'categoria_id' => $categoriaId, // Categoria "Transferência"
            'historico' => $data['historico'],
            'valor' => -abs($data['valor']), // Negativo para saída
            'user_id' => $userId,
        ];
        $this->insert($saida);

        // Movimento de entrada (conta destino)
        $entrada = [
            'data_mov' => $data['data_mov'],
            'conta_id' => $data['conta_destino'],
            'conta_destino_id' => $data['conta_origem'],
            'categoria_id' => $categoriaId, // Categoria "Transferência"
            'historico' => $data['historico'],
            'valor' => abs($data['valor']), // Positivo para entrada
            'user_id' => $userId,
        ];
        $this->insert($entrada);

        $this->db->transComplete(); // Concluir transação

        return $this->db->transStatus(); // Retorna o status da transação
    }

    /**
     * Atualizar transferência (dois movimentos).
     */
    public function updateTransferencia($id, $data)
    {

        // ID da categoria "Transferência" (busque do banco ou defina manualmente)
        $categoriaTransferencia = $this->db->table('categorias')
            ->select('id')
            ->where('nomecategoria', 'Transferência')
            ->get()
            ->getRowArray();

        if (!$categoriaTransferencia) {
            throw new \Exception('Categoria "Transferência" não encontrada no banco de dados.');
        }

        $categoriaId = $categoriaTransferencia['id'];

        $this->db->transStart(); // Iniciar transação

        // Atualizar movimento de saída (conta origem)
        $saida = [
            'data' => $data['data_mov'],
            'conta_id' => $data['conta_origem'],
            'conta_destino_id' => $data['conta_destino'],
            'categoria_id' => $categoriaId, // Categoria "Transferência"
            'historico' => $data['historico'],
            'valor' => -abs($data['valor']),
        ];
        $this->update($id, $saida);

        // Atualizar movimento de entrada (conta destino)
        $entrada = [
            'data' => $data['data_mov'],
            'conta_id' => $data['conta_destino'],
            'conta_destino_id' => $data['conta_origem'],
            'categoria_id' => $categoriaId, // Categoria "Transferência"
            'historico' => $data['historico'],
            'valor' => abs($data['valor']),
        ];
        $this->update($id + 1, $entrada); // Supondo que a entrada seja o próximo registro do par

        $this->db->transComplete(); // Concluir transação

        return $this->db->transStatus();
    }

    /**
     * Excluir transferência (dois movimentos).
     */
    public function deleteTransferencia($id)
    {
        $this->db->transStart(); // Iniciar transação

        // Excluir os dois movimentos relacionados à transferência
        $this->delete($id);       // Movimento de saída
        $this->delete($id + 1);   // Movimento de entrada (supondo estrutura sequencial)

        $this->db->transComplete(); // Concluir transação

        return $this->db->transStatus();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

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
