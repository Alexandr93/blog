<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.09.15
 * Time: 15:49
 */

namespace Framework\Validation\Filter;


class NotBlank
{
    /**
     * @param $param
     * @return array|bool
     */
    public function check($param)
    {
        return (strlen($param) == 0) ? ['error' => ' field can not be empty'] : true;
    }
}