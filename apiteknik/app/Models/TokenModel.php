<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenModel extends Model
{
    protected $table      = 'token';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $useSoftDeletes = true;
    protected $allowedFields = ['user', 'token', 'data_name', 'get', 'post', 'put', 'delete', 'status'];
}
