<?php

class Routing
{

   private $_routes;

   public function __construct()
   {
      $path = ROOT . '/config/routes.php';
      $this->_routes = include($path);
   }

   /**
    * Возвращает запрашиваемый URI
    */
   private function getURI()
   {
      if (!empty($_SERVER['REQUEST_URI'])) {
         $str = strip_tags(trim($_SERVER['REQUEST_URI'], '/'));
         $replace = "index.php?XDEBUG_SESSION_START=netbeans-xdebug";
         $position = strpos($str, $replace);
         if(is_int($position)){
            $str = str_replace($str, $replace, '');
         }
         return $str;
      }
   }
   
   
   /**
    * Определяется метод обработки запроса
    */
   public function run()
   {
      
      $uri = $this->getURI();

    
      if($uri == "favicon.ico"){
         exit;
      }
       // Проверить наличие такого запроса в routes.php
      foreach ($this->_routes as $pattern => $path) {
         $result = preg_match("~$pattern~", $uri);
         
         if ($result) {
            
            // Формируется внутренний путь
            $innerRoute = preg_replace("~$pattern~", $path, $uri);
                                    
            // Определяется контроллер, action, параметры

            $parameters = explode('/', $innerRoute);

            $controllerName = array_shift($parameters) . 'Controller';
            $controllerName = ucfirst($controllerName);

            $actionName = 'action' . ucfirst(array_shift($parameters));
            
            $controllerFile = ROOT . '/controllers/' .
                        $controllerName . '.php';

            if (file_exists($controllerFile)) {
               include_once($controllerFile);
            }

            $controllerObject = new $controllerName;
            
            $result1 = call_user_func_array(array($controllerObject, $actionName), $parameters);
            
            if ($result1 != null) {
               break;
            }
         }
      }
   }
}
