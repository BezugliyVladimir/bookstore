<?php

/**
 * Функция __autoload для автоматического подключения классов
 * @param string $class - имя класса 
 */
function __autoload($class)
{
    // Директории, в которых могут находиться нужные классы
    $array_paths = array(
      '/models/',
      '/submodules/',
      '/controllers/',
    );

    
   foreach ($array_paths as $path) {

     // Путь к файлу с классом
     $path = ROOT . $path . $class . '.php';

     // Подключается файл если он есть
     if (is_file($path)) {
        include_once $path;
     }
   }
}
