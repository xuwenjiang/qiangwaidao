<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';

    public function getUser($id)
    {
        $row = $this->fetchRow('id = ' . $id);
        if($row){
            $row->toArray();
        }
        return $row;
    }
    
    public function getAllUsers($where = null)
    {
        $rows = $this->fetchAll($where);
        if($rows){
            return $rows->toArray();
        } else {
            return array();
        }
    }
    
    public function getUserByField($name,$value)
    {
        $where = $name.' = \''.$value .'\'';
        $row = $this->fetchRow($where);
        if($row){
            $row->toArray();
        }
        return $row;
    }


    public function addUser($userData)
    {
        if (!is_array($userData))
        {
            return;
        }
        return $this->insert($userData);
    }

    public function updateUser($id, $userData)
    {
        return $this->update($userData, 'id = ' . (int) $id);
    }

    public function deleteUser($id)
    {
        return $this->delete('id =' . (int) $id);
    }

}

