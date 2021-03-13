<?php

/**
 * Контроллер GoodController
 * Карточка товара
 */
class GoodController extends AbstractController
{
   /**
    * Action для страницы просмотра товара
    * @param string $goodId - идентификатор товара
    */
   public function actionIndex($goodId)
   {
      $categories = array();
      $categories =   $this->getCategories();
      
      // карточка товара
      $good = array();
      $goodId = self::clearInt($goodId);
      $good = $this->_good->getGoodById($goodId, $this->_goodsIdInBasket);
      
      $specialGood = array();
      $specialGood = $this->_good->getSpecialGood($this->_goodsIdInBasket);
          
      require_once(ROOT . '/views/good/view.php');
      
      return true;
   }
}
