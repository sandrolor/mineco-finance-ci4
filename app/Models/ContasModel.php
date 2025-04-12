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
    protected $allowedFields    = ['nomeconta', 'grupo_id', 'user_id'];

    public function getAllWithGroup($search = null, $userId = null)
    {
        $builder = $this->select('contas.*, grupos_contas.nomegrupo AS grupo_nomegrupo')
            ->join('grupos_contas', 'grupos_contas.id = contas.grupo_id', 'left')
            ->where('contas.user_id', $userId);

        if ($search) {
            $builder->like('contas.nomeconta', $search);
        }

        return $builder->orderBy('contas.nomeconta', 'ASC')->paginate(10);
    }

    public function getAllGroups()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('grupos_contas')
            ->select('id, nomegrupo')
            ->where('user_id', session()->get('user_id')) // 👈 filtro essencial
            ->orderBy('nomegrupo', 'ASC');

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
