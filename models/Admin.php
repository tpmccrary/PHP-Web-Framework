<?php 

namespace app\models;

class Admin extends User
{

    public $allUsers = [];
    public function getAllUsers()
    {
        $sql = "SELECT * FROM user";
        $data = $this->getAll($sql);
        $this->allUsers = $data;
        return $data;
    }

}
