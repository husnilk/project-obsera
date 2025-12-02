<?php

namespace App\Models;

class krsModel extends BaseRestModel
{
    protected $DBGroup = 'sia';
    protected $table      = 's_krs_detil';
    protected $primaryKey = 'krsdtId';

    protected $useAutoIncrement = true;

    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'krsdtBobotNilai', 'krsdtBobotPembulatan', 'krsdtKodeNilai', 'krsdtKodeKelompok', 'krsdtTanggalPengubahanNilai', 'krsdtAplikasiPengubah', 'krsdtUserNamaPengubah', 'krsdtUserProfilPengubah', 'krsdtNilaiAngka'
    ];
}
