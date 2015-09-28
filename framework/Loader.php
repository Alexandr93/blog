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


    /**
     *  пустой метод __clone, закрываем доступ вне класса
     */
    private function __clone(){}

    public static function getInstance()
    {
        if(null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

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

        // для определения имени файла обходим пространства имён из абсолютного
        // имени класса в обратном порядке
        while (false !== $pos = strrpos($prefix, '\\')) {

            // сохраняем завершающий разделитель пространства имён в префиксе
            $prefix = substr($class, 0, $pos + 1);

            // всё оставшееся — относительное имя класса
            $relative_class = substr($class, $pos + 1);

            // пробуем загрузить соответсвующий префиксу и относительному имени класса файл
            $mapped_file = self::loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // убираем завершающий разделитель пространства имён для следующей итерации strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // файл так и не был найден
        return false;

    }

    /**
     * @param $prefix
     * @param $relative_class
     * @return bool|string
     */
    protected static function loadMappedFile($prefix, $relative_class)
    {
        // есть ли у этого префикса пространства имён какие-либо базовые директории?
        if (isset(self::$prefixes[$prefix]) === false) {
            return false;
        }

        // ищем префикс в базовых директориях
        foreach (self::$prefixes[$prefix] as $base_dir) {

            // заменяем префикс базовой директорией,
            // заменяем разделители пространства имён на разделители директорий,
            // к относительному имени класса добавляем .php
            $file = $base_dir
                . str_replace('\\', '/', $relative_class)
                . '.php';

            // если файл существует, загружаем его
            if (self::requireFile($file)) {
                // ура, получилось
                return $file;
            }
        }

        // файл так и не был найден
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
