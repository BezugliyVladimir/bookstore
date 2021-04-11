<?php include ROOT . '/views/maquette/header.php'; ?>
<div id="center-wrapper" class='clearfix'>
  <div class="case">
    <div id="secondary">
      <div class="box-title catalog"><span>Каталог</span></div>
      <ul class="left_menu">
        <?php foreach($categories as $category):?>
        <li class="<?=$category['parity']?>"><a href="/category/<?php echo $category['id']; ?>"><?=$category['name']; ?></a></li>
        <?php endforeach;?>
      </ul>
      <div class="banner"> 
        <div class='prod-author'>
            <span title='Автор'><?=$good['author']?></span>     
        </div>
      </div>
    </div>
  </div>
  <div id="box-main">
    <div class="case">
      <div class="main-content">
        <div id = "title-main-content"><!-- Main content area -->
          <h1>Оформление заказа</h1>
        </div>
        <div class = 'results'>
        <?php if ($result): ?>
          <p>Заказ оформлен. Наш менеджер свяжется с Вами.</p>
        <?php else: ?>
        </div>
        <div class = 'results'>
          <p id = 'total'>Выбрано товаров: <span class = 'bold'><?=$_SESSION['quantity']?></span>, стоимостью <span class = 'bold'><?=$_SESSION['price']?> грн</span></p> 
          <p id = 'title-help'>Чтобы оформить заказ, заполните пожалуйста форму:</p>
        </div>
        <div id="orderform" class = "clearfix">
          <form action="/basket/order" method="post">
            <div class="field">
              <label for="name clearfix">Ваше имя: </label>
              <input type="text" name="name" id="name" size="40" value="<?=$name?>"><!--
              --><span id="help1" class="help" style="position:absolute"></span>
            </div>      
            <div class="field clearfix">
              <label for="phone">Телефон для связи: </label>
              <input type="text" name="phone" size="40" id="phone" placeholder ="067-123-34-78" value=""><!--
              --><span id="help2" class="help" style="position:absolute"></span>
            </div>
            <div class="field clearfix">
              <label for="comment">Комментарий к заказу: <br><span style="font-size:10px"></label>
              <textarea name="comment" id="comment" cols="40" rows="4"></textarea>
            </div>
            <div id="metka" class='button'>
              <input type="button"  value="Заказать" onclick="changeForm(this.form)">           
            </div>
          </form>
        </div>
        <?php endif;?>
      </div><!-- / Main content area -->
    </div>
  </div>
</div>
<?php include ROOT . '/views/maquette/footer.php'; ?>