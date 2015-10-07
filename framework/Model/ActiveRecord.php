<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 18:10
 */
namespace Framework\Model;
abstract class ActiveRecord
{
    protected $table;//gettable
    protected $key_field_name='id';
    public function save(){
        $fields=get_object_vars($this);

        $colums='';//Get column names for $table
        foreach($colums as $col){
            if(array_key_exists()){
            $sql_part=sprintf('`%s`=%s', $col, $fields[$col]);
            }
           $sql=implode(', ', $sql_part);
            //$sql.='WHERE '.$this->key_field_name.'='.$this->
        }
    }
    public static function find($id){
        return 'I am page number '.$id;
    }//regexp 'all' \d
}