<?php

namespace App\models;

use App\Core\Model;
use App\Core\App;

class Local extends Model
{
    protected $table = 'local';
    protected $id;
    protected $direction;
    protected $province;
    protected $country;
    protected $phone;
    protected $email;
    protected $description;

    public function getTxt($id_local) {
        return $this->db->findLocal($this->table, $id_local);
    }

    public function getLocal() {
        $local = $this->db->selectAll($this->table);
        return $local[0]["id_local"];
    }
}
