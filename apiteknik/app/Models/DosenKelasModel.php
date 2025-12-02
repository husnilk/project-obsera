<?php

namespace App\Models;

class DosenKelasModel extends BaseRestModel
{
    protected $DBGroup = 'sia';
    protected $table      = 's_dosen_kelas';
    protected $primaryKey = 'dsnkKlsId';

    protected $useAutoIncrement = true;

    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'dsnkDsnPegNip ', 'dsnkDosenKe', 'dsnkIsBolehInputNilaiOnline'
    ];
}
