<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 18:10
 */
namespace Framework\Model;

use Framework\DI\Service;

abstract class ActiveRecord
{


    public function save(){

        $fields=get_object_vars($this);//переменные с внесенными значениями для сохранения

        $valuesArr=array();
        $columsArr=array();//Get column names for $table
        foreach($fields as $col=>$val) {

                $valuesArr[] = $val;
                $columsArr[] = $col;

                //закоментировал поле $id в классе User нигде не применяется
        }
            $db=Service::get('db');
            $colum='('.implode(', ', $columsArr).')';
            $values='("'.implode('", "', $valuesArr).'")';
            $query='INSERT INTO `'.static::getTable().'` '.$colum.' VALUES '.$values;
            //$query='INSERT INTO users (email, password, role) VALUES ("va11sa@mail.ru", "13", "ROLE_USER")';

            //$db->query($query);
            print_r($query);


    }
    public static function getTable(){


    }
    public static function find($id){

        $db =Service::get('db');
        $query = 'SELECT * FROM '.static::getTable();
        if(gettype($id)=='integer'){
            $query.=' WHERE id = :id';
        }
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = ($id == 'all') ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->fetch(\PDO::FETCH_OBJ);
        return $result;
    }

    public static function findByEmail($email){

        $email=htmlspecialchars_decode($email);
        $db=Service::get('db');
        $query = 'SELECT * FROM ' . static::getTable() . ' WHERE email = :email';
        $stmt=$db->prepare($query);
        $stmt->execute([':email' => $email]);
        $result=$stmt->fetch(\PDO::FETCH_OBJ);
        return $result;
    }
}