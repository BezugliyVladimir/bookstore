<?php

/**
* Класс Category - модель для работы с категориями товаров
*/

class Category
{
   
   protected $_db;
     
   /**
    * Инициализация экземпляра PDO, 
    * предоставляющего подключение к базе данных
    */
   public function __construct(PDO $db)
   {
      $this->_db = $db;
   }
   
   
   /**
    * Удаляет ссылку на объект PDO
    */
   public function __destruct()
   {
      unset($this->_db);
   }
   
   
   /**
    * Делает выборку категорий товара из базы данных и возвращает их в виде массива
    * @return array - категории товаров
    */
   public function getCategories()
   {
      $categories = array();
      $sql = 'SELECT id, name, parity FROM category ORDER BY sort_order ASC';
      $result = $this->_db->query($sql);
      $i = 0;
      while ($row = $result->fetch()) {
         $categories[$i]['id'] = $row['id'];
         $categories[$i]['name'] = $row['name'];
         $categories[$i]['parity'] = $row['parity'];
         $i++;
      }
      unset($result);
      return $categories;
   }
}