<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nomecategoria', 'grupo_id', 'user_id'];

    public function getAllWithGroup($search = null, $userId = null)
    {
        $builder = $this->select('categorias.*, grupos_categorias.nomegrupo AS grupo_nomegrupo')
                        ->join('grupos_categorias', 'grupos_categorias.id = categorias.grupo_id', 'left')
                        ->where('categorias.user_id', $userId);

        if ($search) {
            $builder->like('categorias.nomecategoria', $search);
        }

        return $builder->orderBy('categorias.nomecategoria', 'ASC')->paginate(10);
    }

    public function getAllGroups()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('grupos_categorias')
        ->select('id, nomegrupo')
        ->where('user_id', session()->get('user_id')) // 👈 filtro essencial
        ->orderBy('nomegrupo', 'ASC');
        return $builder->get()->getResultArray();
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
