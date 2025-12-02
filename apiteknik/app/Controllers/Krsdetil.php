<?php

namespace App\Controllers;


class Krsdetil extends BaseRest
{
    protected $ModelName = 'App\Models\krsModel';
    protected $data_name = 'krsdetil';
    protected $AllowedSelectField = []; // Costum select field -> replace [] with ['id', 'user', 'pass']
    protected $AllowedSelectKey = ['krsdtId']; //-> enable this line to enable costum allow selected field, default = Model->AllowedField

}
