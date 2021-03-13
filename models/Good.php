<?php

/**
 * Класс Good - модель для работы с товарами
 */

class Good
{

    const DISPLAY_ON_PAGE = 2;

    const DISCOUNT_FACTOR = 0.7;

    const DISCOUNT_FACTOR_FOR_SPECIAL = 0.4;

    const DISPLAY_RECOMMENDED_GOODS = 4;

    private $_pdo;

    private $_typeDiscount = '';

    /**
    * Инициализация экземпляра PDO, 
    * предоставляющего подключение к базе данных
    */
    public function __construct(PDO $db)
    {
         $this->_pdo = $db;
    }

    public function  __destruct()
    {
       unset($this->_pdo);
    }



   /**
    * Делает выборку последних товаров из базы данных и возвращает их в виде массива
    * @param array $goodsIdInBasket - идентификаторы товаров из корзины
    * @param integer $count - количество показываемых товаров на странице
    * @return array
    */      
   public function getLastGoods( $goodsIdInBasket = array(), $count = self::DISPLAY_ON_PAGE)
   {     
      $count = (int) $count;
      $goods = array();
      $sql = "SELECT catalog.id, catalog.title, catalog.author, catalog.path, catalog.category_id, catalog.pubyear, catalog.price, 
                            catalog.available, catalog.is_discount, catalog.is_special, category.name as category from catalog AS catalog, category as category
                        WHERE catalog.category_id = category.id AND catalog.status = '1' ORDER BY catalog.id DESC LIMIT $count";
     
      $stmt = $this->_pdo->query($sql);
      $goods = $this->Object2Array($stmt, $goodsIdInBasket);
      unset($stmt);
      return $goods;
   }
    
   /**
    * Получает массив из результирующего набора и возвращает его
    * @param object PDOStatement - результирующий набор выборки товаров из базы данных
    * @param array $goodsIdInBasket - идентификаторы товаров из корзины
    * @return array - товары
    */
   private function Object2Array(PDOStatement $stmt, $goodsIdInBasket)
   {
      $i = 0;
      $goods = array();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
         $goods[$i]['id'] = $row['id'];
         $goods[$i]['title'] = $row['title'];
         $goods[$i]['author'] = $row['author'];
         $goods[$i]['category_id'] = $row['category_id'];             
         $goods[$i]['path'] = $row['path'];
         $goods[$i]['pubyear'] = $row['pubyear'];
         $goods[$i]['price'] = $row['price'];
         $goods[$i]['available'] = $row['available'];
         $goods[$i]['is_discount'] = $row['is_discount'];
         $goods[$i]['is_special'] = $row['is_special'];
         $goods[$i]['newprice'] = $this->getNewPrice($row['id'], $row['price'], $row['is_discount'], $row['is_special']);
         $goods[$i]['status'] = self::getViewOfContent($row['id'], $goodsIdInBasket,    $row['available']);
         $goods[$i]['category'] = $row['category'];
         $i++;
      }
      unset($stmt);
      return $goods; 
   }
   
   /**
    * Возвращает разные значения HTML-строки в зависимости от статуса товара ("Купить"/"Товар в корзине"/"Нет в наличии") 
    * @param integer $id - идентификатор товара
    * @param array $goodsIdInBasket - идентификаторы товаров из корзины
    * @param integer $available - статус наличия товара на складе
    * @return string - HTML-строка
    */
   private static function getViewOfContent($id, $goodsIdInBasket, $available = 1 )
   {
      if(in_array($id, $goodsIdInBasket)){
          $view = "<div class = 'prod-basket'><a href = '/basket'>Товар в корзине</a></div>";
      }elseif($available == 0){
          $view = "<div class = 'not-available'><span class = 'black'>Нет в наличии</span></div>";
      }else{
          $view = "<div class = 'button'><a href='/basket/add/$id '>Купить</a></div>";
      }
      return $view;
   }
         
   /**
    * Возвращает товары по выбранной категории товара
    * 
    * @param integer $categoryId - идентификатор 
    * @param array $goodsIdInBasket - массив товаров из корзины
    * @param mixed $page - текущая страница
    * @return array - товары из выбранной категории
    */
   public function getGoodsByCategory($categoryId, $goodsIdInBasket, $page=1)
   {
      if ($categoryId) {
         $offset = ($page-1) * self::DISPLAY_ON_PAGE;
         $goods = array();
         $sql = "SELECT catalog.id, title, author, category_id, pubyear, price, path, available, 
                        is_discount, is_special, category.name as category
                                 FROM catalog, category
                                 WHERE catalog.category_id = category.id AND catalog.status = '1'
                                       AND category.id = '$categoryId'
                                 ORDER BY id ASC                           
                                 LIMIT " . self::DISPLAY_ON_PAGE . " 
                                 OFFSET $offset";
         
         $stmt = $this->_pdo->query($sql);
         
         $goods = $this->Object2Array($stmt, $goodsIdInBasket);
         unset($stmt);
         return $goods;            
      }
   }
         
   /** Есть 3 варианта скидок на товар:
     * 1 тип скидки: "discount", 
     * 2 тип скидки: "special",
     * 3 тип - нет скидки.
     * 
     * Метод возвращает новую цену товара в зависимости от типа скидки
     * @param integer $id         - идентификатор товара
     * @param integer $discount - флаг типа скидки "discount"
     * @param integer $special    - флаг типа скидки "special"
     * @param string $price    - начальная цена товара
     * @return float|false    - новая цена товара|нет новой цены
     */
   private function getNewPrice($id, $price, $discount = 0, $special = 0)
   {  
      if($discount or $special){
         /* Скидки "discount" и "special" могут принимать значения 1 и 0 (есть скидка/ нет скидки),
           каждый товар может иметь только один тип скидки ('discount' или 'special') */
         // определяем текущий тип скидки
         $this->_typeDiscount = self::getTypeOfDiscount($discount); 
         $newPrice = self::recalculationPrice($this->_typeDiscount, $price);
         $this->addNewPrice($id, $newPrice);
      }else{
         $newPrice = false; // нет скидки
      }
      return $newPrice;
   }
         
   /**
    * Определяет тип скидки товара и возвращает тип скидки в виде строки
    * 
    * @param integer $discount - флаг скидки
    * @return string - тип скидки
    */
   private static function getTypeOfDiscount($discount)
   {
      if($discount){
         $_typeDiscount = 'discount';
      }else{
         $_typeDiscount = 'special';
      }
      return $_typeDiscount;
   }
             
  /**
   * Пересчитывает цену товара и возвращает новую цену товара 
   *
   * @param string $_typeDiscount - тип скидки на товар
   * @param string $price - базовая цена товара
   * @return float - новая цена товара
   */
   private static function recalculationPrice($_typeDiscount, $price)
   {
      switch($_typeDiscount){
         case 'discount' : $newPrice = $price * self::DISCOUNT_FACTOR;break;
         case 'special'    : $newPrice = $price * self::DISCOUNT_FACTOR_FOR_SPECIAL;break;
         default: $newPrice = false;
      }
      return $newPrice;
   }
   
   /**
    * Добавляет в БД новую цену товара по идентификатору товара
    *
    * @param integer $id - идентификатор товара
    * @param float $newPrice - новая цена товара
    * @return boolean - результат добавления
    */
   private function addNewPrice($id, $newPrice)
   {     
      $sql = "UPDATE catalog SET newprice = :newPrice WHERE id = :id";
      $stmt = $this->_pdo->prepare($sql);
      $stmt->bindParam(':newPrice', $newPrice, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      return $stmt->execute();
   }
      
   /**
    * Возвращает выбранный товар
    *
    * @param string $id - идентификатор товара
    * @param array $goodsIdInBasket - массив идентификаторов товаров из корзины
    * 
    * @return array - массив товаров из выбранной категории товаров
    */
   public function getGoodById($id, $goodsIdInBasket = array())
   {
      $sql = "SELECT id, title, author, category_id, pubyear, price, path, available, is_discount, is_special, description, code, publish, language FROM catalog WHERE id= $id";
      
      $stmt = $this->_pdo->query($sql);
      $goods = $stmt->fetch(PDO::FETCH_ASSOC);
      unset($stmt);
      $goods['is_available'] = self::getTextByAvailable($goods['available']);
      $goods['newprice'] = self::getNewPrice($goods['price'], $goods['is_discount'], $goods['is_special']);
      $goods['status'] = self::getViewOfContent($goods['id'], $goodsIdInBasket,   $goods['available']);
      return $goods;
   }
         
   /**
    * Возвращает статус наличия товара на складе в виде строки
    *
    * @param integer $available - статус наличия товара на складе 
    * 
    * @return string -   статус наличия товара в строковом виде
    */
   private static function getTextByAvailable($available)
   {
      if($available){
          return 'Есть в наличии';
      }else{
          return 'Нет в наличии';
      }
   }
      
   /**
    * Возвращает характеристики товара из категории 'Специальное предложение'
    *
    * @param array $goodsIdInBasket - массив идентификаторов товаров, находящихся в корзине  
    * @return array - массив характеристик товара из категории "Специальное предложение"
    */
   public function getSpecialGood($goodsIdInBasket = array())
   {           
      $this->_typeDiscount = 'special';    
      $specialGood = array();
      
      $query = "SELECT id, title, author, category_id, pubyear, price, path, is_special, available FROM catalog WHERE is_special = 1";
      $stmt = $this->_pdo->query($query);
      $specialGood = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $specialGood[0]['special'] = self::recalculationPrice($this->_typeDiscount, $specialGood[0]['price']);
      $specialGood[0]['status'] = self::getViewOfContent($specialGood[0]['id'], $goodsIdInBasket,   $specialGood[0]['available']);
      unset($stmt);
      return $specialGood;
   }
               
   /**
    * Выборка рекомендуемых товаров из базы данных
    * 
    * @param array $goodsIdInBasket - массив идентификаторов товаров, находящихся в корзине    
    * @param int $count - количество отображаемых товаров на странице
    * @return array - рекомендуемые товары
    */
   public function getRecommendedGoods($goodsIdInBasket = 0, $count= self::DISPLAY_RECOMMENDED_GOODS)
   {
      $sql = "SELECT catalog.id as id, catalog.category_id as category_id, title, author, pubyear,
                     price, path, is_special, is_discount, available, category.name as category 
                        FROM catalog, category
                        WHERE catalog.category_id = category.id AND catalog.status = '1'
                           AND is_recommended = '1' AND available = 1
                        ORDER BY id DESC 
                        LIMIT $count";
      $goods = array();
      $stmt = $this->_pdo->query($sql);
      $goods = self::Object2Array($stmt,$goodsIdInBasket);
      unset($stmt);
      return $goods;
   }
    
   /**
    * Выборка количества товаров по идентификатору категории товара
    * @param integer $categoryId - идентификатор категории товара 
    * @return integer - количество товаров данной категории товара
    */
   public function getCountGoodsByCategory($categoryId)
   {
      $sql = 'SELECT count(id) AS count '.
                  'FROM catalog '.
                  'WHERE status="1" ' . 
                     'AND category_id = :category_id';
      $stmt = $this->_pdo->prepare($sql);
      $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch();
      return $row['count'];
   }
}   