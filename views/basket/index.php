<?php include ROOT . '/views/maquette/header.php'; ?>
<div id="center-wrapper" class='clearfix'>
  <div class="case">
    <div id="secondary">
      <div class="box-title catalog"><span>Каталог</span></div>
        <ul class="left_menu">
          <?php foreach($categories as $category):?>
           <li class="<?=$category['parity']?>"><a href="/category/<?=$category['id']; ?>"><?=$category['name']; ?></a></li>
          <?php endforeach;?>
        </ul>
<?php foreach($specialGood as $item):?>       
      <div class="box-title stock">Специальное предложение</div>
      <div class="border-box">
        <a href="/good/<?=$item['id']?>">
          <div class="prod-title random center"><object><a href='index.php?page=details&id=4' title='<?=$item['title']?>'><?=$item['title']?></a></object></div>
          <div class="product_img">     
            <img id="rand" src='\template\picture\duma_gmk.jpg' alt='' border='0' height='100px' width='100px'/>
            <div class="hot"><img src='\template\picture\icon\special.png'/></div>
          </div>
          <div class='prod-author center'>
            <span title='Автор'><?=$item['author']?></span>     
          </div>
        </a>
        <div class="clearfix">
          <div class="prod-price">
            <div class = 'old-price'><span class='reduce'><?=$item['price'] .' грн'?></span></div>
            <div class = 'new-price'><span id='hot-price'><?=$item['special'] .' грн' ?></span></div>
          </div>
          <div class='order stock'><?=$item['status']?></div>
        </div>
      </div>        
<?php  endforeach;?>
      <div class="banner"> 
        <a href="#">
          <h3>Место для Вашей книги</h3>
        </a> 
      </div>
    </div>
  </div>
  <div id="box-main">
    <div class="case">
      <div class="main-content"><!-- main content area -->
        <div id = "title-main-content">
          <h1>Ваша корзина</h1>
        </div>
        <form id = "cart" method = "post" action = "/basket">
         <?php 
          $numberGood = 1;
          foreach($goodsInBasket as $good): 
         ?>
             <div class='prod-box'>
               <div id='delete'>
                 <a href="/basket/delete/<?= $good['id']?>" title="Удаление товара из корзины"></a>
               </div>
               <div id="code-item">
                 <span id='code-title'>Код товара: </span>
                 <span id='code-number'><?=$good['code']?></span>
               </div>
               <a id="product" href='/good/<?=$good['id']?>'>
                 <div class='prod-title center'>
                   <object>
                     <a href='/good/<?=$good['id']?>' title='<?=$good['title']?>'><?=$good['title']?></a>
                   </object>
                 </div>
                 <div class='prod-img'>
                   <img src='<?=$good['path']?>' title = '<?=$good['title']?>' alt='<?=$good['title']?>' border='0' height='100px' width='100px'/>
                 </div>
                 <div class = 'prod-author center'>
                   <span title='Автор'><?=$good['author']?></span>
                 </div>  
               </a>
               <div class = 'clearfix'>
                 <div class='prod-price'>
                   <span class='price'><?=$good['price']?></span>
                 </div>
                 <div class='order'>
                   <span id='count'>Количество:</span>
                   <select name = "<?=$numberGood?>" form = "cart" onchange = "result()">
                     <?php  for($i=1; $i<=10; ++$i): ?>
                     <option value = "<?=$i?>" <?php if($good['quantity']==$i){echo "selected = 'selected'";}else{echo "";}?>><?=$i?></option>
                     <?php endfor;?>
                   </select><br/>
                 </div>
               </div>
             </div>
         <?php 
          $numberGood++; 
          endforeach; 
          if($_SESSION['quantity']):
         ?>
          <div id = "result">
            <p align = "center"><b>Всего товаров в корзине на сумму:<?=$_SESSION['price']?> грн</b></p>
          </div>
          <div id = "buttons">
            <div class="buttons" id="translusent" align="center">
              <input type ="submit"  id = "change" form = "cart" value = "Пересчитать" disabled="disabled">
            </div>
            <div align="center" class="buttons">
              <div id="butt">
                <input type="button" class="button" id = "submit" value="Оформить заказ!"
                                onClick="location.href='/basket/order'" />
              </div>
            </div>
          </div>
          <?php else:?>
          <div id="no-goods"><span id ="empty-basket">В корзине товаров нет!</span></div>
          <?php endif; ?>
        </form>
      </div><!-- / main content area-->
    </div>
  </div>
</div>
<?php include ROOT . '/views/maquette/footer.php'; ?>