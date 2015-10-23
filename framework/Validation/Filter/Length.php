<?php
namespace Framework\Validation\Filter;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.09.15
 * Time: 15:48
 */
class Length
{
    public $min;
    public $max;
    /**
     * @param $min
     * @param $max
     */
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }
    /**
     * @param $var
     * @return array|bool
     */
    public function check($var)
    {
        $length = strlen($var);
        if($length < $this->min)
            return ['error' => 'must be more than '.$this->min.' characters'];
        if($length > $this->max)
            return ['error' => 'must be less than '.$this->max.' characters'];
        return true;
    }
}