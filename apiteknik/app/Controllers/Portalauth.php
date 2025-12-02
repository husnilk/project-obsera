<?php

namespace App\Controllers;


class Portalauth extends BaseRest
{
    protected $ModelName = 'App\Models\UserPortalModel';
    protected $data_name = 'portalauth';
    protected $AllowedSelectField = []; // Costum select field -> replace [] with ['id', 'user', 'pass']
    protected $AllowedSelectKey = ['tusrNama', 'tusrPassword']; //-> enable this line to enable costum allow selected field, default = Model->AllowedField

    public function index()
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        $input = $this->request->getJSON(true);
        if (!$input) {
            return $this->respond(['messages' => 'Data must send in JSON Format'], 400);
        }
        // dd($input);
        if (!(isset($input['tusrNama']) and isset($input['tusrPassword']))) {
            return $this->respond(['messages' => 'Invalid JSON Format'], 400);
        }
        $data = $this->Model->select($this->AllowedSelectField)->where('tusrPassword', $input['tusrPassword'])->where('tusrNama', $input['tusrNama'])->findAll(); // Get all data when there are no transmitted key(s)
        if ($data) {
            return $this->respond($data, 200);
        } else {
            return $this->respond(['messages' => 'Invalid Username or Password '], 403);
        }
    }
}
