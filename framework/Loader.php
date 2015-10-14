<?php

/**
 * Class Loader
 *
 * @property array namespaceMap
 */

class Loader{

    protected static $prefixes = array();
    protected static $_instance;

    public function __construct(){

      spl_autoload_register(array($this, 'load'));
    }




    public static function getInstance()
    {
        if(null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     *  пустой метод __clone, закрываем доступ вне класса
     */
    private function __clone(){}
    /**
     * добавление пути
     * принимает на вход 2 параметра
     * @param string $prefix строка со значением пространства имен
     * @param string $base_dir хранит значение пути
     * @param bool $prepend
     */
    public static function addNamespacePath($prefix, $base_dir, $prepend = false){
        {
            // нормализуем префикс пространства имён
            $prefix = trim($prefix, '\\') . '\\';

            // нормализуем базовую директорию так, чтобы всегда присутствовал разделитель в конце
            $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

            // инициализируем массив префиксов пространства имён
            if (isset(self::$prefixes[$prefix]) === false) {
                self::$prefixes[$prefix] = array();
            }

            // сохраняем базовую директорию для префикса пространства имён
            if ($prepend) {
                array_unshift(self::$prefixes[$prefix], $base_dir);
            } else {
                array_push(self::$prefixes[$prefix], $base_dir);
            }
        }

    }

    /**
     * @param $class
     * @return string
     */
    public static function load($class){

        $prefix = $class;

        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            $relative_class = substr($class, $pos + 1);
            $mapped_file = self::loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }
            $prefix = rtrim($prefix, '\\');
        }
        return false;

    }

    /**
     * @param $prefix
     * @param $relative_class
     * @return bool|string
     */
    protected static function loadMappedFile($prefix, $relative_class)
    {
        if (isset(self::$prefixes[$prefix]) === false) {
            return false;
        }
        foreach (self::$prefixes[$prefix] as $base_dir) {

            $file = $base_dir
                . str_replace('\\', '/', $relative_class)
                . '.php';

            // если файл существует, загружаем его
            if (self::requireFile($file)) {
                // ура, получилось
                return $file;
            }
        }
        return false;
    }

    /**
     * Если файл существует, загружеаем его
     * @param string $file файл для загрузки.
     * @return bool true, если файл существует, false — если нет.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
           require $file;
            return true;
        }
        return false;
    }

}
$loader=Loader::getInstance();
Loader::addNamespacePath("Framework\\",__DIR__);
//$loadFunction = '\Loader::loadClass';