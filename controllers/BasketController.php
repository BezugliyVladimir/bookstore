<?php

/**
 * Контроллер BasketController - содержит методы для работы с корзиной
 */
class BasketController extends AbstractController
{
   
   // идентификатор заказа, уникальный для каждой сессии
   protected $_orderid = '';

   /**
    * Установка основных данных для работы с корзиной 
    */
   public function __construct() {
      parent::__construct();
      $this->_orderid = $this->_basket->getOrderid();
   }

   /**
    * Добавляет товар в корзину
    * @param string $id - идентификатор товара 
    */
   public function actionAdd($id) {
      $id = self::clearInt($id);
      $this->_basket->add2Basket($id);
      $referrer = $_SERVER['HTTP_REFERER'];
      header("Location: $referrer");
   }
   
   /**
    * Action для работы с корзиной пользователя
    * Формирует страницу корзины пользователя    
    */
   public function actionIndex() {
 
      $categories = array();
      $categories = $this->getCategories();
      
      $specialGood = array();
      $specialGood = $this->getSpecialGood();

      // товары в корзине
      $goodsInBasket = array();
      $goodsInBasket = $this->_basket->myBasket();
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $this->_basket->recalculate($goodsInBasket);
      }
      require_once( ROOT . "/views/basket/index.php");
      return true;
   }
   
   /**
    * Удаляет товар из корзины по идентфикатору товара
    * @param string $id - идентификатор товара
    */
   public function actionDelete($id) {
      $id = self::clearInt($id);
      $this->_basket->changeStatus($id);
      $this->_basket->quantityItemInBasket();
      $this->_basket->priceItemInBasket();
      header("Location: /basket");
   }
   
   /**
    * Оформляет заказ пользователя 
    */
   public function actionOrder() {
      if (!$_SESSION['quantity']) {
         header("Location: /");
      }
      $categories = $this->getCategories();
      
      $name = "";
      
      $userId = null;
      
      // Результат оформления заказа
      $result="";
      
      if (User::isAuth()){
         // Если пользователь авторизован
         // получаем имя пользователя из БД
         $userId = self::clearInt(User::isLogged());
         $user = new User($this->_pdo);
         $userData = $user->getUserById($userId);
         $name = $userData['name'];
      }
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $name     = self::clearStr($_POST['name']);
         $phone    = self::clearStr($_POST['phone']);
         $comment  = self::clearStr($_POST['comment']);
         $orderid  = $this->_orderid;
         $datetime = time();
         
         $order = new Order($this->_pdo);
         // сохранение заказа в базу данных
         $result = $order->saveOrder($name, $phone, $comment, $userId, $orderid, $datetime);
         if($result){
            $adminEmail = 'InfoProgressBook@gmail.com';
            $message = '<a href="http://bookstore26/admin/orders">Список заказов</a>';
            $subject = 'Новый заказ!';
            mail($adminEmail, $subject, $message);
            
            // В сессии может быть $_SESSION['user'], который нужно сохранить
            // поэтому сессию в целом сохраняем
            $_SESSION['quantity'] = 0;
            $_SESSION['price'] = 0;
            $_SESSION['orderid'] = null;
         }
      }
      require_once(ROOT . '/views/basket/order.php');
      return true;
   }
}
