<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 18:10
 */
namespace Framework\Model;


use Framework\DI\Service;
use Framework\Exception\DatabaseException;

abstract class ActiveRecord
{


    /**
     *  save data from the form
     */
    public function save()
    {
        $fields = get_object_vars($this);
        $columns = '';
        $values = '';
        $querValues=array();
        foreach ($fields as $col => $val) {
            $columns .= $col . ', ';
            $values .= ':' . $col . ', ';
            $querValues[':'.$col]=$val;
        }
        $columns = '(' . substr($columns, 0, -2) . ')';
        $values = '(' . substr($values, 0, -2) . ')';
        $db = Service::get('db');
        $query = 'INSERT INTO `' . static::getTable() . '` ' . $columns . ' VALUES ' . $values;
        $stmt=$db->prepare($query);
         $res= $stmt->execute($querValues);
        if($res==false){
            throw new DatabaseException('Saving is failed');
        }
    }

    public static function getTable()
    {


    }

    /**
     * find record by id
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {

        $db = Service::get('db');
        $query = 'SELECT * FROM ' . static::getTable();
        if (gettype($id) == 'integer') {
            $query .= ' WHERE id = :id';
        }
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = ($id == 'all') ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->fetch(\PDO::FETCH_OBJ);
        return $result;
    }

    /**
     * update table, optimization security
     * @param $field
     * @param $fieldValue
     */
    public function update($field, $fieldValue)
    {
        $fields = get_object_vars($this);
        $query = '';
        $values=array();
        foreach ($fields as $col => $val) {
            $query .= $col . '=:' . $col . ', ';
            $values[':'.$col]=$val;
        }
        $query = trim($query);
        $query = substr($query, 0, -1);
        $db = Service::get('db');
        $query = 'UPDATE `' . static::getTable() . '` SET ' . $query . ' WHERE ' . $field . '=:fieldValue';
        $stmt = $db->prepare($query);
        $values[':fieldValue']= $fieldValue;
        $res=$stmt->execute($values);
        if($res==false){
            throw new DatabaseException('Update is failed');
        }
    }

    /**
     * Find in db by email
     * @param $email
     * @return mixed
     */
    public static function findByEmail($email)
    {
        $email = htmlspecialchars_decode($email);
        $db = Service::get('db');
        $query = 'SELECT * FROM ' . static::getTable() . ' WHERE email = :email';
        $stmt = $db->prepare($query);
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $result;
    }
}