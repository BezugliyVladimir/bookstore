<?php

/**
 * Контроллер CatalogController
 * Каталог товаров
 */
class CatalogController extends AbstractController
{
    
    /**
     * Action для страницы "Каталог товаров"
     */ 
    public function actionIndex()
    { 
       $categories = array();
       $categories = $this->getCategories();
       
       $lastGoogs = array();
       $lastGoods = $this->getLastGoods($this->_goodsIdInBasket, 8);
       
       $specialGood = array();
       $specialGood = $this->getSpecialGood($this->_goodsIdInBasket);

         require_once(ROOT . '/views/catalog/index.php');
         return true;
    }
            
   /**
    * Action для страницы "Категория товаров"
    * @param string $categoryId - идентификатор категории
    * @param string $page - номер страницы
    */
   public function actionCategory($categoryId, $page = '1')
   {
      
      $categories = array();
      $categories = $this->getCategories();

      // товары выбранной категории
      $categoryId = self::clearInt($categoryId);
      $categoryGoods = array();
      $categoryGoods = $this->_good->getGoodsByCategory($categoryId, $this->_goodsIdInBasket, $page);

      // товар категории "Специальное предложение"
      $specialGood = array();
      $specialGood = $this->getSpecialGood($this->_goodsIdInBasket);

      // Общее количетсво товаров (необходимо для постраничной навигации)
      $total = $this->_good->getCountGoodsByCategory($categoryId);
      $page = self::clearInt($page);
      
      // Постраничная навигация
      $pagination = new Pagination($total, $page, Good::DISPLAY_ON_PAGE, 'page-');

      require_once(ROOT . '/views/catalog/category.php');
      return true;
   }
}
