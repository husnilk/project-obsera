<?php

namespace App\Controllers;


class PortalData extends BaseRest
{
    protected $ModelName = 'App\Models\UserPortalModel';
    protected $data_name = 'portaldata';
    protected $AllowedSelectField = ['tusrNama', 'tusrProfil', 'tusrEmail', 'tusrProdiKode ']; // Costum select field -> replace [] with ['id', 'user', 'pass']
    protected $AllowedSelectKey = ['keyword', 'ThakrId', 'start', 'end']; //-> enable this line to enable costum allow selected field, default = Model->AllowedField

    public function index()
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        // Check the input of get request
        $input = $this->request->getGet();
        if (isset($input['keyword']) and isset($input['ThakrId']) and isset($input['start']) and isset($input['end'])) {
            if ($this->keys_check($this->AllowedSelectKey, $input)) {
                return $this->respond(['messages' => 'Unknown key(s)'], 400);
            }
            // SELECT * FROM `t_user` WHERE `tusrNama` LIKE '%1993%' AND `tusrProfil` LIKE '%%' AND `tusrThakrId` = 2
            $data = $this->Model->select($this->AllowedSelectField)->where("`tusrNama` LIKE '%" . $input['keyword'] . "%' OR `tusrProfil` LIKE '%" . $input['keyword'] . "%' AND `tusrThakrId` = " . $input['ThakrId'])->findAll($input['end'] - $input['start'] + 1, $input['start']); // Get data with variuos conditions based on transmitted key(s)
            if ($data) return $this->respond($data, 200);
            return $this->respond(['messages' => 'Data not foundfound'], 404);
            // return $this->respond(['messages' => "`tusrNama` LIKE '%" . $input['nip'] . "%' AND `tusrProfil` LIKE '%" . $input['nama'] . "%' AND `tusrThakrId` = 2"], 404);
        }
        return $this->respond(['messages' => 'Unknown key(s)'], 400);
        // return $this->respond($data, 200);
    }
}
