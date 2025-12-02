<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\API\ResponseTrait;
use App\Models\TokenModel;
use App\Models\LogModel;

class BaseRest extends ResourcePresenter
{
    protected $ModelName; // = 'App\Models\UjicobaModel';
    protected $data_name; // = 'ujicoba';
    protected $AllowedSelectField = []; // Costum select field -> replace [] with ['id', 'user', 'pass']
    // protected $AllowedSelectKey = ['id', 'status']; //-> enable this line to enable costum allow selected field, default = Model->AllowedField

    use ResponseTrait;

    public function __construct()
    {
        $this->Model = model($this->ModelName);
        $this->TokenModel = new TokenModel;
        $this->LogModel = new LogModel;
        $this->primaryKey = $this->Model->getPrimaryKey();
        if (!isset($this->AllowedSelectKey)) {
            $this->AllowedSelectKey = $this->Model->getAllowedFields();
        }
    }

    // -------------------- GET METHOD (SELECT) --------------------
    public function index()
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        // Check the input of get request
        $input = $this->request->getGet();
        if ($input) {
            if ($this->keys_check($this->AllowedSelectKey, $input)) {
                return $this->respond(['messages' => 'Unknown key(s)'], 400);
            }
            $data = $this->Model->select($this->AllowedSelectField)->where($input)->findAll(); // Get data with variuos conditions based on transmitted key(s)
            if ($data) return $this->respond($data, 200);
            return $this->respond(['messages' => 'Data not found'], 404);
        }
        $data = $this->Model->select($this->AllowedSelectField)->findAll(); // Get all data when there are no transmitted key(s)
        return $this->respond($data, 200);
    }

    public function show($id = null) // Find data based on primary key (id)
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        $data = $this->Model->select($this->AllowedSelectField)->find($id);
        if ($data) return $this->respond($data, 200);
        return $this->respond(['messages' => 'Data not found'], 404);
    }

    // -------------------- POST METHOD (INSERT) --------------------
    public function create()
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        $input = $this->request->getJSON(true);
        if (!$input) {
            return $this->respond(['messages' => 'Data must send in JSON Format'], 400);
        }
        if ($this->Model->insert($input) === false) {
            return $this->respond([
                'messages' => 'Error Validation',
                'errors' => $this->Model->errors()
            ], 400);
        }
        return $this->respond(['messages' => 'New data inserted'], 200);
    }


    // -------------------- PUT METHOD (UPDATE) --------------------
    public function update($id = null)
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        $data = $this->Model->find($id);
        if ($data) {
            $input = $this->request->getJSON(true);
            if (!$input) {
                return $this->respond(['messages' => 'Data must send in JSON Format'], 400);
            }
            if ($this->Model->update($id, $input) === false) {
                return $this->respond([
                    'messages' => 'Error Validation',
                    'errors' => $this->Model->errors()
                ], 400);
            }
            return $this->respond(['messages' => 'Data updated'], 200);
        }
        return $this->respond(['messages' => 'Data failed to update'], 400);
    }
    public function updatebulk()
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        $input = $this->request->getJSON(true);
        if (!$input) {
            return $this->respond(['messages' => 'Data must send in JSON Format'], 400);
        }
        $error = 0;
        $err = [];
        foreach ($input as $i) :
            if (!isset($i[$this->primaryKey])) {
                $error++;
                $err = array_merge($err, [[
                    'data' => $i,
                    'errors' => 'id no set'
                ]]);
                break;
            }
            $data = $this->Model->find($i[$this->primaryKey]);
            if ($data) {
                if ($this->Model->update($i[$this->primaryKey], $i) === false) {
                    $error++;
                    $err = array_merge($err, [[
                        'data' => $i,
                        'errors' => $this->Model->errors()
                    ]]);
                }
            } else {
                $error++;
                $err = array_merge($err, [[
                    'data' => $i,
                    'errors' => 'id not found'
                ]]);
            }
        endforeach;
        $this->save_log($input);
        if ($error > 0) {
            return $this->respond([
                'messages' => 'Several data not updated',
                'errors' => $err
            ], 400);
        }
        return $this->respond(['messages' => 'All data updated'], 200);
    }


    // -------------------- DELETE METHOD (DELETE) --------------------
    public function delete($id = null)
    {
        // Check Auth
        if ($this->auth_check()) {
            return $this->respond(['messages' => 'Prohibited access'], 403);
        }
        $data = $this->Model->find($id);
        if ($data) {
            $this->Model->delete($id);
            return $this->respond(['messages' => 'Data has been deleted'], 200);
        }
        return $this->respond(['messages' => 'id not found'], 404);
    }

    // -------------------- Auth Check --------------------
    protected function auth_check()
    {
        $token = $this->request->getHeaderLine('Token');
        $data = $this->TokenModel->where(['data_name' => $this->data_name, 'token' => $token, 'status' => 1])->first();
        if ($data) {
            if (!is_null($data['ip'])) {
                $ip = $this->request->getIPAddress();
                // d($ip);
                // dd($data['ip']);
                if ($ip == $data['ip']) {
                    return false;
                }
                return true;
            }
            $method = $this->request->getMethod();
            switch ($method) {
                case 'get':
                    $accessibility = $data['get'];
                    break;

                case 'post':
                    $accessibility = $data['post'];
                    break;

                case 'put':
                    $accessibility = $data['put'];
                    break;

                case 'delete':
                    $accessibility = $data['delete'];
                    break;

                default:
                    $accessibility = 0;
                    break;
            }
            if ($accessibility == 0) {
                return true;
            }

            return false;
        }
        // dd($data);
        return true;
    }

    protected function save_log($data)
    {
        $token = $this->request->getHeaderLine('Token');
        $user_data = $this->TokenModel->where(['data_name' => $this->data_name, 'token' => $token, 'status' => 1])->first();
        if ($user_data) {
            $log_data['user'] = $user_data['user'];
            $log_data['data_name'] = $this->data_name;
            $log_data['token'] = $token;
            $log_data['ip'] = $this->request->getIPAddress();
            $log_data['method'] = $this->request->getMethod();
            $log_data['json_data'] = json_encode($data);
            // dd($log_data);
            $this->LogModel->insert($log_data);
            return true;
        }
        return false;
    }

    // -------------------- Additional Function for Key(s) Check --------------------
    protected function keys_check(array $keys, array $arr)
    {
        $eror = array_diff_key(array_flip($keys), $arr);
        $size_eror = sizeof($eror);
        $size_keys = sizeof($keys);
        $size_arr  = sizeof($arr);
        if ($size_eror > ($size_keys - $size_arr)) return true;
        return false;
    }
}
