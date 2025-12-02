<?php

namespace App\Controllers;

use App\Models\DosenKelasModel;

class CekData extends BaseController
{
    public function __construct()
    {
        $this->DosenKelasModel = new DosenKelasModel;
    }

    public function index()
    {
        $db = \Config\Database::connect('sia');
	//$query = $db->query('SELECT * FROM `s_krs_detil` WHERE `krsdtKlsId` = 410005349');
        //$query = $db->query('SELECT * FROM `s_kelas` WHERE `klsId` = 410005349');
	$query = $db->query('SELECT * FROM `s_dosen_kelas` WHERE `dsnkDsnPegNip` LIKE 197708162005011002 AND `dsnkKlsId` = 410005349 ORDER BY `s_dosen_kelas`.`dsnkKlsId` DESC');
	//$query = $db->query('SELECT * FROM `s_dosen_kelas` WHERE `dsnkDsnPegNip` LIKE 197708162005011002 ORDER BY `s_dosen_kelas`.`dsnkKlsId` DESC');
	//$query = $db->query('SELECT * FROM `s_krs_detil` WHERE `krsdtKrsId` = 420030643');
        $data = $query->getResult();
        dd($data);
        // $data = $this->DosenKelasModel->where('dsnkKlsId', '510006509')->findAll();
        // var_dump($data);
    }
}
