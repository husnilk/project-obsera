<?php

namespace App\Models;

class UserPortalModel extends BaseRestModel
{
    protected $DBGroup = 'portal';
    protected $table      = 't_user';
    protected $primaryKey = 'tusrNama';

    protected $useAutoIncrement = true;

    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'tusrNama', 'tuserPassword'
    ];
}
