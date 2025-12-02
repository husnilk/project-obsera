<?php

namespace App\Models;

class UjicobaModel extends BaseRestModel
{
    protected $table      = 'ujicoba';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $useSoftDeletes = false;
    protected $allowedFields = ['user', 'pass', 'status'];

    protected $validationRules      = [
        'user'      => 'required',
        'pass'      => 'required',
        'status'    => 'required|in_list[1,0]'
    ];
}
