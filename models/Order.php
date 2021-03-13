<?php
/**
 * Класс Order - модель для работы с заказами
 */
class Order
{
   
   protected $_pdo;
   
   /**
    * Инициализация экземпляра PDO, 
    * предоставляющего подключение к базе данных
    */   
   public function __construct(PDO $pdo){
      $this->_pdo = $pdo;
   }
   
   /**
    * Сохранение заказа 
    * @param string $name - имя пользователя
    * @param string $phone - телефон
    * @param string $comment - комментарий
    * @param integer $userId - идентификатор пользователя
    * @param integer $orderid - идентификатор заказа
    * @param integer $datetime - временная метка
    * @return boolean - результат выполнения метода
    */
   public function saveOrder($name, $phone, $comment, $userId, $orderid, $datetime)
   {   
      $sql = "INSERT INTO orders (order_id, user_name, phone, comment, user_id, datetime) 
                    VALUES (:order_id, :user_name, :phone, :comment, :user_id, :datetime)";
      
      $stmt = $this->_pdo->prepare($sql);
      $stmt->bindParam(':order_id', $orderid, PDO::PARAM_STR);
      $stmt->bindParam(':user_name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
      $stmt->bindParam(':datetime', $datetime, PDO::PARAM_INT);
      $result = $stmt->execute();
      return $result;
   }


}