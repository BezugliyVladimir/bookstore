<?php

/**
 * Класс User - модель для работы с пользователями
 */
 
class User
{
  
   private $_pdo;
   
   /**
    * Инициализация экземпляра PDO, 
    * предоставляющего подключение к базе данных
    */
   public function __construct(PDO $db){
      $this->_pdo = $db;
   }
   
   
   /**
    * Регистрация пользователя 
    * @param string $name - имя
    * @param string $email - e-mail
    * @param string $password - пароль
    * @param string $orderid - идентификатор заказа
    * @return boolean - результат выполнения метода
    */
   public function register($name, $email, $password, $orderid)
   {
      $hash = $this->getHash($password);
      
      $sql = "INSERT INTO authorization (name, email, hash, order_id)
                   VALUES (:name, :email, :hash, :orderid)";

      $stmt = $this->_pdo->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
      $stmt->bindParam(':orderid', $orderid, PDO::PARAM_STR);
      $result = $stmt->execute();
      
      unset($stmt);
      return $result;
            
    }
   /**
    * Получает пароль, возвращает хэш
    * @param string $password - пароль
    * @return string - хэш
    */      
   private function getHash($password){
      $hash = password_hash($password, PASSWORD_DEFAULT);
      return $hash;
   }
   
   
   /**
    * Проверяет пароль на соответствие хэшу
    * @param string $password - пароль
    * @param string $hash - хэш
    * @return boolean - результат проверки
    */   
   public function checkHash($password, $hash){
      return password_verify($password, $hash);
   }
   
   /**
    * Проверяет имя: более 1-го символа
    * @param string $name - имя
    * @return boolean - результат выполнения метода
    */
   public function checkName($name)
   {
      if (mb_strlen($name, 'UTF-8') >= 2) {
         return true;
      }
         return false;
   }

   /**
    * Проверяет телефон: не меньше, чем 10 символов
    * @param string $phone - телефон
    * @return boolean - результат выполнения метода
    */
   public function checkPhone($phone)
   {
      if (strlen($phone) >= 10) {
         return true;
      }
      return false;
   }

   /**
    * Проверяет пароль: более 7 символов
    * @param string $password - пароль
    * @return boolean - результат выполнения метода
    */
   public function checkPassword($password)
   {
         if(mb_strlen($password, 'UTF-8') > 7) {
            return true;
         }
         return false;
   }

   /**
    * Проверяет email
    * @param string $email - email
    * @return boolean - результат выполнения метода
    */
   public function checkEmail($email)
   {
         $pattern = "/^([a-zA-Z0-9_-]+\.)*[a-zA-Z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/";
         if (preg_match($pattern, $email)) {
               return true;
         }
         return false;
   }

   /**
    * Проверяет наличие пользователя с таким email
    * @param string $email - email
    * @return array - данные пользователя\false если пользователя нет
    */
   public function checkUserExists($email)
   {
      $sql = 'SELECT id, name, email, hash FROM authorization WHERE email = :email';
      $stmt = $this->_pdo->prepare($sql);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      return $result;
   }
      

   /**
    * Редактирование данных пользователя
    * @param integer $id - идентификатор пользователя
    * @param string $name - имя
    * @param string $password - пароль
    * @return boolean - результат выполнения метода
    */
   public function change($id, $name, $password)
   {
      $hash = $this->getHash($password);
      
      $sql = "UPDATE authorization 
               SET name = :name, hash = :hash 
                  WHERE id = :id";
      $result = $this->_pdo->prepare($sql);
      $result->bindParam(':id', $id, PDO::PARAM_INT);
      $result->bindParam(':name', $name, PDO::PARAM_STR);
      $result->bindParam(':hash', $hash, PDO::PARAM_STR);
      return $result->execute();
   }


   /**
    * Запоминаем пользователя в сессии
    * @param integer $userId - идентификатор пользователя
    */
   public static function saveUser($userId)
   {
      $_SESSION['user'] = $userId;
   }

   /**
    * Возвращает идентификатор пользователя, если он авторизирован,
    * если нет - перенаправляет на страницу входа
    * @return integer - идентификатор пользователя
    */
   public static function isLogged()
   {
      if (isset($_SESSION['user'])) {
         return $_SESSION['user'];
      }

      header("Location: /user/login");
   }

   /**
    * Проверяет является ли пользователь гостем
    * @return boolean - результат выполнения метода
    */
   public static function isAuth()
   {
      if (isset($_SESSION['user'])) {
         return true;
      }
      return false;
   }

   

   /**
    * Возвращает пользователя с указанным идентификатором
    * @param integer $id - идентификатор пользователя
    * @return array - информация о пользователе
    */
   public function getUserById($id)
   {
      $sql = 'SELECT name, email, order_id FROM authorization WHERE id = :id';
      $result = $this->_pdo->prepare($sql);
      $result->bindParam(':id', $id, PDO::PARAM_INT);
      $result->execute();
      $user = $result->fetch(PDO::FETCH_ASSOC);
      return $user;
   }

}
