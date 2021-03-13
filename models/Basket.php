<?php

/**
 * Класс Basket - модель для работы с корзиной покупателя
 */

class Basket
{
   // идентификатор заказа
   private $_orderid;
   
   // Экземпляр класса PDO для работы с базой данных
   private $_pdo = null;
    
   /**
    *  Инициализирует параметры корзины в сессии 
    *  @param PDO $pdo - объект для работы с базой данных
    */
   public function __construct($pdo)
    {  
      $this->_pdo = $pdo;
      if(!isset($_SESSION['orderid'])){
         $this->_orderid = md5(uniqid($_SERVER['REMOTE_ADDR'], true));
         $this->saveBasket();
         if(!isset($_SESSION['quantity'])){
            $this->setStartPriceQuantity();
         }
      }else{
         $this->_orderid = unserialize(base64_decode($_SESSION['orderid']));
         if(!isset($_SESSION['quantity'])){
            $this->setStartPriceQuantity();
         }
      }
   }
    
   /**
    *Сохраняет идентификатор заказа в сессию
    */
   private function saveBasket()
    {
      $this->_orderid = base64_encode(serialize($this->_orderid));
      $_SESSION['orderid'] = $this->_orderid;
   }
   
   /**
    * Возвращает идентификатор заказа
    */
   public function getOrderid()
   {
      return $this->_orderid;
   }
   
   /**
    * Инициализирует количество товара и цену в сессии   
    */
   private function setStartPriceQuantity()
   {
      $_SESSION['price']= 0;
      $_SESSION['quantity'] = 0;
   }
   
  /**
   * Делает выборку всех идентификаторов товаров корзины 
   * @return array - идентификаторы товара из корзины
   */
   public function getGoodsIdFromBasket()
   {
      $orderid = $this->_orderid;
      $goodsId = array();
      // status = 1 - товар не был удален из корзины
      $sql = "SELECT goods_id FROM basket WHERE order_id = '$orderid' AND status = 1";
      $stmt = $this->_pdo->prepare($sql);
      $stmt->execute();
      $stmt->bindColumn('goods_id', $id);
      while($row = $stmt->fetch(PDO::FETCH_BOUND)){
         $goodsId[] = $id;
      }
      return $goodsId;
   }
   
   /**
    * Добавляет товар в корзину по идентификатору товара
    * @param integer - идентификатор товара
    */   
   public function add2basket($id){
      $datetime = time();
      $orderid = $this->_orderid;
      $quantity = 1;
      $query = "SELECT price, newprice FROM catalog WHERE id = $id";
      $stmt = $this->_pdo->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
      $price    = $row['price'];
      
      // Цена для товаров по скидке
      $newprice = $row['newprice'];
      
      // Если товар по скидке, в корзину добавляем цену со скидкой
      if($newprice){
          $price = $newprice;
      }
      $stmt->closeCursor();
         
      $sql = "INSERT INTO basket(goods_id, price, quantity, order_id, datetime)
                     VALUES (:id, :price, :quantity, :order, :datetime)";
      $stmt1 = $this->_pdo->prepare($sql);
      $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt1->bindParam(':price', $price, PDO::PARAM_STR);
      $stmt1->bindParam(':quantity', $quantity, PDO::PARAM_INT);
      $stmt1->bindParam(':order', $orderid, PDO::PARAM_STR);
      $stmt1->bindParam(':datetime', $datetime, PDO::PARAM_INT);
      $stmt1->execute();
      $stmt1->closeCursor();
      $this->quantityItemInBasket();
      $this->priceItemInBasket();
      return true;
   }
       
    
   /**
    * Считает количество заказанных книг в корзине
    * и сохраняет его в сессии
    */
   public function quantityItemInBasket(){
      $orderid  = $this->_orderid;
      $results = array();
      $quantity = 0;
      
      // status = 1 - товар не был удален из корзины
      $sql = "SELECT quantity FROM basket WHERE basket.order_id  = '$orderid' AND status = 1";
      $pdoSt = $this->_pdo->query($sql);
      $results = $pdoSt->fetchAll(PDO::FETCH_ASSOC);
      $pdoSt->closeCursor();
      foreach($results as $result){
         $quantity += $result['quantity'];
      }
      $_SESSION['quantity'] = $quantity; 
    }
    
   /**
    * Считает цену заказанных книг в корзине
    * и сохраняет ее в сессии
    */
   public function priceItemInBasket()
   {
      $orderid = $this->_orderid;
      $price = 0;
      // status = 1 - товар не удален из корзины
      $sql = "SELECT price, quantity FROM basket
                     WHERE order_id = '$orderid' AND status = 1";
      $stmt = $this->_pdo->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);//МАССИВ МАССИВОВ
      $stmt->closeCursor();
      foreach($result as $item){
      $price += $item['quantity'] * $item['price'];
      }
      $_SESSION['price'] = $price;     
   }
      
   
   /**
    * Выборка информации о товарах в корзине
    * 
    * @return array - информация о товарах
    */
   public function myBasket(){
      $orderid = $this->_orderid;
      
      // status = 1 - товар не удален из корзины
      $sql = "SELECT basket.goods_id, catalog.title, catalog.author, catalog.pubyear, ".
                        "basket.quantity, basket.price, catalog.path, catalog.code ".
             "FROM catalog, basket ".
                 "WHERE basket.goods_id = catalog.id ".
                       "AND order_id = '$orderid' ".
                       "AND basket.status = 1";
      $stmt = $this->_pdo->query($sql);
      $i = 0;
      $goods = array();
      while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
         $goods[$i]['id'] = $rows['goods_id'];
         $goods[$i]['title'] = $rows['title'];
         $goods[$i]['author']= $rows['author'];
         $goods[$i]['pubyear'] = $rows['pubyear'];
         $goods[$i]['quantity'] = $rows['quantity'];
         $goods[$i]['price'] = $rows['price'];
         $goods[$i]['path'] = $rows['path'];
         $goods[$i]['code'] = $rows['code'];
         $i++;          
      }
      $stmt->closeCursor();
      return $goods;
   }
    
  /**
   * Добавляет количество товара в корзине
   * 
   * @return boolean - результат работы метода
   */
   public function addQuantity($id, $quantity, $datetime)
   {
      $orderid = $this->_orderid;
      $sql = "UPDATE basket
                     SET 
                        quantity = :quantity,
                        datetime = :datetime
                     WHERE order_id = :orderid 
                       AND goods_id = :id";
      $stmt = $this->_pdo->prepare($sql);
      $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
      $stmt->bindParam(':datetime', $datetime, PDO::PARAM_INT);
      $stmt->bindParam(':orderid', $orderid, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      return $stmt->execute();
   }
      
  /**
   * Пересчитывает количество товара и цену в корзине
   * Сохраняет новую цену и количество товара в сессии
   *
   * @param &$goodsInBasket - ссылка на массив товаров из корзины
   */
   public function recalculate(&$goodsInBasket)
   {
      // номер книги
      $numberGood = 1;
      if($goodsInBasket && $_POST[$numberGood]){
         
         // обнуление в сессии количества книг и цены в корзине для пересчета
         $_SESSION['quantity'] = 0;
         $_SESSION['price'] = 0;
         foreach($goodsInBasket as &$item){
            $datetime = time();
            $item['quantity'] = (int)$_POST[$numberGood];
            $this->addQuantity($item['id'], $item['quantity'], $datetime);
            $_SESSION['quantity'] +=  $item['quantity'];
            $_SESSION['price'] += $item['quantity'] * $item['price'];
            $numberGood++;
         }
      }
   }
    
   /**
    * 'Удаляет' товар из корзины по идентификатору
    * путем изменения его статуса с 1 на 0
    * 
    * @param integer - идентификатор товара
    * @return boolean - результат работы метода
    */
   public function changeStatus($id)
   {
      $orderid = $this->_orderid;
      $sql = "UPDATE basket SET status = 0 WHERE goods_id = :id AND order_id = :orderid";
      $stmt = $this->_pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->bindParam(':orderid', $orderid, PDO::PARAM_STR);
      return $stmt->execute();   
   }
}