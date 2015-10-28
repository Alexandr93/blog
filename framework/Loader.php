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
     *
     */
    private function __clone(){}
    /**
     * @param string $prefix
     * @param string $base_dir
     * @param bool $prepend
     */
    public static function addNamespacePath($prefix, $base_dir, $prepend = false){
        {
            $prefix = trim($prefix, '\\') . '\\';

            $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

            if (isset(self::$prefixes[$prefix]) === false) {
                self::$prefixes[$prefix] = array();
            }
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

            if (self::requireFile($file)) {

                return $file;
            }
        }
        return false;
    }

    /**
     * If file exists load him
     * @param string $file
     * @return bool true
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
$loader::addNamespacePath("Framework\\",__DIR__);
