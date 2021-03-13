<?php

/**
 * Контроллер MainController - отвечает за работу главной чтраницы
 */

class MainController extends AbstractController
{
 
    /**
    * Action для работы главной страницы
    */
    public function actionIndex(){
         
      // категории товаров
      $categories = array();
      $categories = $this->getCategories();
      
      // последние товары
      $lastGoods = array();
      $lastGoods = $this->getLastGoods($this->_goodsIdInBasket, 8);
      
      // товар категории "Специальное предложение"
      $specialGood = array();
      $specialGood = $this->getSpecialGood($this->_goodsIdInBasket);
      
      // рекомендуемые товары
      $recommendedGoods = array();
      $recommendedGoods = $this->getRecommendedGoods($this->_goodsIdInBasket, 4);
      
      require_once(ROOT . '/views/main/index.php');
      return true;
   }
}
