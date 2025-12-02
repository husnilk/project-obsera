<?php

namespace App\Models;

class LogModel extends BaseRestModel
{
    protected $table      = 'log';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $dateFormat       = 'datetime';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user', 'data_name', 'token', 'ip', 'method', 'json_data'];

    protected $validationRules      = [
        'user'      => 'required',
        'data_name' => 'required',
        'token'     => 'required',
        'method'    => 'required',
        'json_data' => 'required'
    ];
}
