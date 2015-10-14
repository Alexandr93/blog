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
    protected $table;//gettable
    //protected $key_field_name='id';
    public static $db;
    public function __construct(){
        self::$db=Service::get('db');
    }
    public function save(){
        $fields=get_object_vars($this);

        $colums=array();//Get column names for $table
        foreach($colums as $col){
            if(array_key_exists($col, $colums)){
            $sql_part=sprintf('`%s`=%s', $col, $fields[$col]);
            }
           $sql=implode(', ', $sql_part);
            //$sql.='WHERE '.$this->key_field_name.'='.$this->
        }
    }
    public static function getTable(){

        return 'posts';
    }
    public static function find($id){



        $db =Service::get('db');
        if($id == 'all')
            $query = 'SELECT * FROM '.static::getTable();
        else
            $query = 'SELECT * FROM '.static::getTable().' WHERE id = :id';
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = ($id == 'all') ? $stmt->fetchAll(\PDO::FETCH_OBJ) : $stmt->fetch(\PDO::FETCH_OBJ);
        //print_r($result);
        return $result;
    }//regexp 'all' \d
}