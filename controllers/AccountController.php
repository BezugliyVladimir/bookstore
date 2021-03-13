<?php

/**
 * Контроллер AccountController
 * Кабинет пользователя
 */
class AccountController extends AbstractController
{
   
   // идентификатор заказа, уникальный для каждой сессии
   protected $_orderid;
   
   // объект модели User
   public $user;
   
   // идентификатор пользователя
   protected $_userId;
   
   /**
    * Установка основных данных с личным кабинетом пользователя
    */
   public function __construct()
   {
      parent::__construct();
      $this->_userId = User::isLogged();  
      $this->user = new User($this->_pdo); 
   }
   
   /**
    * Удаляет созданные в конструкторе объекты  
    */
   public function __destruct()
   {
      unset($this->user);
      parent::__destruct();
   }
      
      
   /**
    * Action для страницы "Личный кабинет"
    * Получает данные о пользователе
    */
   public function actionIndex()
   {
         
      $userData = $this->user->getUserById($this->_userId);
      
      require_once(ROOT . '/views/account/index.php');
      return true;
   }

   /**
    * Action для страницы "Редактирование данных пользователя"
    */
   public function actionChange()
   {
      
      $userData = $this->user->getUserById($this->_userId);
      $name     = $userData['name'];

      $result = false;

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         if (isset($_POST['submit'])) {
            $name     = self::clearStr($_POST['name']);
            $password = self::clearStr($_POST['password']);

            $errors = false;

            // Валидация данных пользователя
            if (!$this->user->checkName($name)) {
                  $errors[] = 'Имя должно быть более 1-го символа';
            }
            if (!$this->user->checkPassword($password)) {
                  $errors[] = 'Пароль должен быть более 7-ми символов';
            }
            if ($errors == false) { 
                  $result = $this->user->change($this->_userId, $name, $password);
            }
         }
      }

      require_once(ROOT . '/views/account/change.php');
      return true;
   }
}
