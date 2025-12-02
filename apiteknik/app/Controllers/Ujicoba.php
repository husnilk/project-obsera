<?php

namespace App\Controllers;


class Ujicoba extends BaseRest
{
    protected $ModelName = 'App\Models\UjicobaModel';
    protected $data_name = 'ujicoba';
    protected $AllowedSelectField = []; // Costum select field -> replace [] with ['id', 'user', 'pass']
    protected $AllowedSelectKey = ['id', 'status']; //-> enable this line to enable costum allow selected field, default = Model->AllowedField

}
