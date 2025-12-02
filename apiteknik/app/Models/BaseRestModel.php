<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseRestModel extends Model
{
    protected $skipValidation       = false;
    protected $cleanValidationRules = false;

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getAllowedFields()
    {
        return $this->allowedFields;
    }
}
