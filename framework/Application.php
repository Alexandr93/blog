<?php
namespace Framework;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.09.15
 * Time: 17:29
 */

/**
 * Class Application
 * главный клас приложения
 */
class Application
{
    /**
     * главный метод класа
     * @param $path для вытаскивания конфигов
     */
    public function __construct($path)
    {
        return "Class ".$path;
    }
    public function run(){
        return "Hi, I am class Application";
    }
}