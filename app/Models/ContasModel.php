<?php

namespace App\Models;

use CodeIgniter\Model;

class ContasModel extends Model
{
    protected $table            = 'contas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nomeconta', 'grupo_id'];

    public function getAllWithGroup($search = null)
    {
        $builder = $this->select('contas.*, grupos_contas.nomegrupo AS grupo_nomegrupo')
                        ->join('grupos_contas', 'grupos_contas.id = contas.grupo_id', 'left');

        if ($search) {
            $builder->like('contas.nomeconta', $search);
        }

        return $builder->orderBy('contas.nomeconta', 'ASC')->paginate(10);
    }

    public function getAllGroups()
    {
        $db = \Config\Database::connect();
        $query = $db->table('grupos_contas')->select('id, nomegrupo')->orderBy('nomegrupo', 'ASC')->get();
        return $query->getResultArray();
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
