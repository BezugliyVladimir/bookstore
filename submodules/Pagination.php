<?php

/*
* Класс для генерации постраничной навигации
*/

class Pagination
{

  // количество cсылок навигации на страницу
  private $max = 10;

  //ключ для URL, в который пишется номер страницы
  private $index = 'page';

  // текущая страница 
  private $current_page;

  // общее количество записей
  private $total;

  // количество записей на страницу
  private $limit;

  /**
   * Установка данных для навигации
   * @param integer $total - общее количество записей
   * @param integer $currentPage - текущая страница
   * @param integer $limit - количество записей на страницу
   * @param string $index - ключ для URL
   * 
   */
  public function __construct($total, $currentPage, $limit, $index)
  {
      
      $this->total = $total;

      $this->limit = $limit;

      $this->index = $index;

      $this->amount = $this->amount();

      $this->setCurrentPage($currentPage);
  }

  /**
   *  Для вывода ссылок
   * 
   * @return HTML-код со ссылками навигации
   */
  public function get()
  {
      // ссылки
      $links = null;

      //ограничения для цикла
      $limits = $this->limits();

      $html = '<ul class="pagination">';
      
      // Генерация ссылок
      for ($page = $limits[0]; $page <= $limits[1]; $page++) {
          // Ссылка для текущей страницы
          if ($page == $this->current_page) {
              $links .= '<li class="active"><a href="#">' . $page . '</a></li>';
          } else {
              // Ссылка на другие страницы
              $links .= $this->generateHtml($page);
          }
      }

      // Если ссылки создались
      if (!is_null($links)) {
          // Если номер текущей страницы больше первой
          if ($this->current_page > 1)
             
          // Создаётся ссылка на первую страницу
              $links = $this->generateHtml(1, '&lt;') . $links;

          // Если номер текущей страницы меньше последней
          if ($this->current_page < $this->amount)
          // Создаётся ссылка "На последнюю"
              $links .= $this->generateHtml($this->amount, '&gt;');
      }

      $html .= $links . '</ul>';


      return $html;
  }

  /**
   * Для генерации HTML-кода ссылки
   * @param integer $page - номер страницы
   * 
   * @return string - HTML-строка
   */
  private function generateHtml($page, $text = null)
  {
      
      if (!$text)
      // текстовый элемент - номер страницы
          $text = $page;

      $currentURI = rtrim($_SERVER['REQUEST_URI'], '/') . '/';
      $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI);
      // Формируется HTML-строка ссылки и возвращается
      return
              '<li><a href="' . $currentURI . $this->index . $page . '">' . $text . '</a></li>';
  }

  /**
   *  Определяется место, откуда стартовать
   * 
   * @return array - начало и конец отсчёта
   */
  private function limits()
  {
      // Ссылки слева (чтобы активная ссылка была посередине)
      $left = $this->current_page - round($this->max / 2);

      // начало отсчёта
      $start = $left > 0 ? $left : 1;

      // Если впереди есть как минимум $this->max страниц
      if ($start + $this->max <= $this->amount)
      // Назначаем конец цикла вперёд на $this->max страниц или просто на минимум
          $end = $start > 1 ? $start + $this->max : $this->max;
      else {
          // Конец - общее количество страниц
          $end = $this->amount;

          // Начало - минус $this->max от конца
          $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
      }

      return
        array($start, $end);
  }

  /**
   * Для установки текущей страницы
   * @param mixed $currentPage - текущая страница
   * @return integer - текущая страница
   */
  private function setCurrentPage($currentPage)
  {
      // Номер текущей страницы
      $this->current_page = $currentPage;

      
      if ($this->current_page > 0) {
          
          // Если текущая страница больше общего количества страниц
          if ($this->current_page > $this->amount)
          
              // Устанавливаем страницу на последнюю
              $this->current_page = $this->amount;
      } else
     
          $this->current_page = 1;
  }

  /**
   * Для получения общего числа страниц
   * 
   * @return float - общее количество страниц
   */
  private function amount()
  {
      return round($this->total / $this->limit);
  }

}
