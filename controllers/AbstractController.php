<?php

/**
 * Содержит основные методы и свойства для работы с магазином
 */
 abstract class AbstractController
 {
    // объект для создания соединения с базой данных
    protected $_database;
    
    // объект для доступа к базе данных
    protected $_pdo;
    
    // идентификаторы товаров корзины
    protected $_goodsIdInBasket = array();
    
    // объект модели Good (для работы с товарами)
    protected $_good;
    
    // объект модели Basket (для работы с корзиной)
    protected $_basket;
    
   /**
    * Установка основных данных для работы магазина
    */
   public function __construct()
   {
      $this->_database = new Database();
      $this->_pdo = $this->_database->connect();
   
      $this->_basket = new Basket($this->_pdo);
      if(!empty($_SESSION['quantity'])){
         $this->_goodsIdInBasket = $this->_basket->getGoodsIdFromBasket();
      }
      $this->_good = new Good($this->_pdo);
   }
    
   /**
    * Удаляет созданные в конструкторе объекты 
    */
    public function __destruct()
    {
      unset($this->_basket);
      unset($this->_good);
     unset($this->_pdo);
      unset($this->_database);  
    }
    
    /**
      * Получает категории товаров из базы данных
      * @return array - категории
      */
    public function getCategories()
    {
       $category = new Category($this->_pdo);
       $categories = $category->getCategories();
       unset($category);
       return $categories;  
    }
    
   /**
    * Получает последние товары из базы данных
    * @return array - последние товары
    */
   public function getLastGoods()
   {
      $lastGoods = $this->_good->getLastGoods($this->_goodsIdInBasket, 18);
      return $lastGoods;
   }
    
   /**
    * Получает товар из категории "Специальное предложение"
    * @return array - товар из категории "Специальное предложение"
    */
   public function getSpecialGood()
   {
      $specialGood = $this->_good->getSpecialGood($this->_goodsIdInBasket);
      return $specialGood;
   }
    
   /**
    * Получает рекомендуемые товары
    * @return array - рекомендуемые товары
    */    
   public function getRecommendedGoods()
   {
      $recommendedGoods = array();
      $recommendedGoods = $this->_good->getRecommendedGoods($this->_goodsIdInBasket, 4);
      return $recommendedGoods;
   }
   
   /**
    * Фильтрация данных 
    * @param scalar $data - входящие данные
    * @return integer - отфильтрованные данные
    */
   public static function clearInt($data) {
      $data = abs((int) $data);
      return $data;
   }
   
   /**
    * Фильтрация данных типа string
    * @param string $data - строка
    * @return string - отфильтрованные данные
    */
   public static function clearStr($data){
      $result = strip_tags(trim($data));
      return $result;
   }
}