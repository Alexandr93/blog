<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 21:17
 */

namespace Framework\Validation;

use Framework\Model\ActiveRecord;
use Framework\Exception\ValidationException;

class Validator
{
    protected $errors = NULL;
    protected $rules;
    protected $objVars;
    /**
     * @param $post
     */
    public function __construct($post)
    {

                $this->rules = $post->getRules();
                $this->objVars = get_object_vars($post);

    }
    /**
     * @return bool
     */
    public function isValid()
    {
        foreach($this->rules as $name => $filters)
        {
            foreach($filters as $filter)
            {
                $result = $filter->check($this->objVars[$name]);
                if(is_array($result))
                {
                    $this->errors[$name] = 'Error, '.$name.' '.$result['error'];
                }
            }
        }
        return ($this->errors == NULL);
    }
    /**
     * @return null
     */
    public function getErrors()
    {
        return $this->errors;
    }

}