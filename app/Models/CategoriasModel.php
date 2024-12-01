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
    protected $allowedFields    = ['nomecategoria', 'grupo_id'];

    public function getAllWithGroup($search = null)
    {
        $builder = $this->select('categorias.*, grupos_categorias.nomegrupo AS grupo_nomegrupo')
                        ->join('grupos_categorias', 'grupos_categorias.id = categorias.grupo_id', 'left');

        if ($search) {
            $builder->like('categorias.nomecategoria', $search);
        }

        return $builder->orderBy('categorias.nomecategoria', 'ASC')->paginate(10);
    }

    public function getAllGroups()
    {
        $db = \Config\Database::connect();
        $query = $db->table('grupos_categorias')->select('id, nomegrupo')->orderBy('nomegrupo', 'ASC')->get();
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
