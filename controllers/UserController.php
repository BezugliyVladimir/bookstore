<?php

/**
 * Контроллер UserController - отвечает за работу с пользователями
 */
class UserController extends AbstractController
{
 
   // Идентификатор заказа, уникальный для каждой сессии
   protected $_orderid = '';
   
   // Объект модели User
   protected $_user;
   
   /**
    * Установка основных данных для работы с пользователями
    */
   public function __construct()
   {
      parent::__construct();
      $this->_orderid = $this->_basket->getOrderid();
      $this->_user = new User($this->_pdo);
   }

   /**
    * Удаляет созданные в конструкторе объекты 
    */
   public function __destruct()
   {
      unset($this->_user);
      parent::__destruct();
   }
   
   /**
    * Регистрирует пользователя
    */
   public function actionRegister()
   {
      $name          = '';
      $email         = '';
      $password      = '';
      $result        = false;
      $classInput    = "";
      
      // Флаг ошибок для сообщений об ошибках 
      $errors = false;
      
      // Флаги ошибок для подсвечивания текстовых полей при ошибках
      $className      = "";
      $classEmail     = "";
      $classPassword  = "";
         
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         if(isset($_POST['button'])){
            $name       = self::clearStr($_POST['name']);
            $email      = self::clearStr($_POST['email']);
            $password   = self::clearStr($_POST['password']);

            // цвет границ текстовых полей при ошибке задается классом css:
            $classInput = 'border-error';
            
            // Валидация текстового ввода
            if (!$this->_user->checkName($name)) {
                  $errors[] = 'Имя должно быть более одного символа';
                  $className = $classInput;
            }
            if (!$this->_user->checkEmail($email)) {
                  $errors[] = "Email должен быть вида 'name@gmail.com'";
                  $classEmail = $classInput;
            }
            if (!$this->_user->checkPassword($password)) {
                  $errors[] = 'Пароль должен быть более 7 символов';
                  $classPassword = $classInput;
            }
            if ($this->_user->checkUserExists($email)) {
                  $errors[] = 'Такой email уже существует.';
                  $classEmail = $classInput;
            }
            
            if ($errors == false){
               $result = $this->_user->register($name, $email, $password, $this->_orderid);
            }
         }
      }
      require_once(ROOT . '/views/user/register.php');
      return true;
   }
   
   
   /**
    * Action для страницы "Вход на сайт"
    */
   public function actionLogin()
   {
            
      $email    = false;
      $password = false;
            
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         if (isset($_POST['submit'])) {
           
            $email    = self::clearStr($_POST['email']);
            $password = self::clearStr($_POST['password']);

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!$this->_user->checkEmail($email)) {
                  $errors[] = "Email должен быть вида 'name@gmail.com'";
            }
            if (!$this->_user->checkPassword($password)) {
                  $errors[] = 'Пароль должен быть более 7 символов';
            }

            // Проверяет существует ли пользователь
            $userData         = $this->_user->checkUserExists($email);
            
            // Проверяем соответствие данных
            if($userData){
               /* Если данные соответствуют, сохраняем данные пользователя в сессии
                и направляем его в личный кабинет*/
               if($this->_user->checkHash($password, $userData['hash'])){
                  User::saveUser($userData['id']);
                  header("Location: /account"); 
               }else{
                  $errors[] = 'Неправильное имя пользователя или пароль';
               }
            }else{
               $errors[] = 'Неправильное имя пользователя или пароль';
            }
         }
      }
      require_once(ROOT . '/views/user/login.php');
      return true;
   }

      /**
       * Удаляет данные о пользователе из сессии
       */
      public function actionExit()
      {
         unset($_SESSION["user"]);
         header("Location: /");
      }
}

      