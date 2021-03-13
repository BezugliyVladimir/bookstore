<!DOCTYPE html>
<html>
  <head>
    <title>Главная</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
    <link rel="stylesheet" type="text/css" href="/template/css/styles.css" >  
    <!--[if lte IE 7]>
    <style type="text/css">
    .prod-box{
      display:inline;
      zoom:1;
    }
    </style>
    <![endif]-->
    <script type="text/javascript" src="/template/js/main.js"></script>
    <!--<script type="text/javascript" src="/template/js/register.js"></script>-->
  </head>
  
  <body>
    <div class="wrapper">
      <div class='top'>
        <div id = "wide-band" class = "breadth test">
          <div id = "discount"><img src = "/template/picture/discountF.png" class = "discount"/></div>
        </div>    
      </div>
      <div id="border-header"></div>
      <div id="document-box" class="breadth">
        <div id="hd">
          <div id="header">
            <section class = "row"><!--
                --><section class = "col-1-4-first">
                  <div class="header-content">
                    <div id = "logo">
                       <a href = "\" ><img src="/template/picture/Logo.png" id='progress'/></a>
                    </div>
                  </div>
                </section><!--
               --><section class = "col-1-2">
                  <div class="header-content">
                    <div id="tabs" class = "clearfix">
                      <ul class = "top-menu">
                        <li><a href="/"><span>Главная</span></a></li>
                        <li><a href="/catalog"><span>Каталог</span></a></li>
                        <li><a href="#"><span>Как сделать заказ</span></a></li>
                        <li><a href="#"><span>Обратный звонок</span></a></li>
                        
                      </ul>
                    </div>
                    <div id = "search">
                      <form name = "form1" method = "post" action = "#">
                        <div class='text-search'>
                             <input id='search-text' class ='search-text' type="search" name = "search" placeholder="Введите автора, название или ключевое слово" size = "40"><!--
                          --><input id='search-button' class='search-button' type="submit" name="find" value = "Найти">
                        </div>
                      </form>
                    </div>
                  </div>
                </section><!--  
               --><section class = "col-1-4-second">
                  <div class="header-content">
                    <div id = "register" class="clearfix">
                      <ul class = "personal">
                        <?php if(!User::isAuth()):?>
                        <li><a href = "\user\register"><span>Регистрация</span></a></li> 
                        <li><a href = "\user\login"><span>Войти</span></a></li>
                        <?php else:?>
                        <li><a href = "\account"><span>Кабинет</span></a></li>
                        <li><a href = "\user\exit"><span>Выйти</span></a></li>
                        <?php endif;?>
                      </ul>
                    </div>
                    <div id="basket">
                      <div id="basket-details"><span class = "basket">Кол-во: </span><span id="q-ty"><?=$_SESSION['quantity']?></span> </br>
                        <span id = "border"></span>
                        <span class="basket">Цена: </span><span id="price"><?=$_SESSION['price']?> грн</span>
                      </div>
                      <div id="basket-image">
                        <a href="\basket"><img src="/template/picture/icon/book.svg" alt="" width="60" height="60" border="0" /></a>
                      </div>
                    </div>
                  </div>
                </section>  
            </section>
          </div>
          <div id='contacts'>
            <div class='contacts'>
              <div class="icon"><img src="/template/picture/icon/smartphone.svg"/></div>
              <div id = "telephone" ><span>+38(097)172-56-68</span></div>
            </div><!--
         --><div class='contacts'>
              <div class="icon"><img src="/template/picture/icon/clock.svg"/></div>
              <div id = "schedule"><span>Пн-Пт 8:00-13:00, 14:00-18:00; СБ 9:00-15:00; ВС - выходной</span></div>
            </div><!--
        --><div class ='contacts'>
              <div class="icon"><img src="/template/picture/icon/envelope.svg"/></div>
              <div id = "mail"><span>InfoProgressBook@gmail.com</span></div>
            </div>  
          </div>
        </div>