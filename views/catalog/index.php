<?php include ROOT . '/views/maquette/header.php'; ?>

<div id="center-wrapper" class='clearfix'>
  <div class="case"><!-- позиционируем слева в 'bd'-->
    <div id="secondary">
      <div class="box-title catalog"><span>Каталог</span></div>
        <ul class="left_menu">
          <?php foreach($categories as $category):?>
          <li class="<?=$category['parity']?>"><a href="/category/<?php echo $category['id']; ?>"><?=$category['name']; ?></a></li>
          <?php endforeach;?>
        </ul>
<?php foreach($specialGood as $item):?>       
      <div class="box-title stock">Специальное предложение</div>
      <div class="border-box">
        <a href="/good/<?=$item['id']?>">
          <div class="prod-title random center"><object><a href="/good/<?=$item['id']?>" title='<?=$item['title']?>'><?=$item['title']?></a></object></div>
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
<?php endforeach; ?>
      <div class="banner"> 
        <a href="#">
          <h3>Место для Вашей книги</h3>
        </a> 
      </div>
    </div>
  </div>
    <div id="box-main"><!--позиционируем справа в 'bd'-->
      <div class="case"><!-- position:static-->
        <div class="main-content">
          <div id = "title-main-content">
            <h1>Последние товары</h1>
          </div>
          <!-- Область основного контента -->
        <?php foreach($lastGoods as $good): ?>
          <div class='prod-box'>
            <a href='/good/<?=$good['id']?>'>
              <div class='prod-category'>
                <object>
                  <a href='/category/<?=$good['category_id']?>' title='<?=$good['category']?>'><?=$good['category']?></a>
                </object>
              </div>
              <div class='prod-img'>
                <img src='<?=$good['path']?>' alt='' border='0'/>
                <?php if ($good['is_discount']):?>
                  <div id="discount2"><img src='\template\picture\icon\discount2.png'/></div>
                <?php endif;?>
                <?php if ($good['is_special']):?>
                  <div class="hot"><img src='\template\picture\icon\special.png'/></div>
                <?php endif;?>
              </div>
              <div class='prod-title'>
                <object>
                  <a href='/good/<?=$good['id']?>' title='<?=$good['title']?>'><?=$good['title']?></a>
                </object>
              </div>
                  
              <div class='prod-author'>
                <span title='Автор'><?=$good['author']?></span>     
              </div>
            </a>
            <div class='clearfix'>
              <div class='prod-price'>
                <?php if (!$good['newprice']):?>          
                <span class='price'><?=$good['price'].' грн'?></span>
                <?php  else:?>
                <div class = 'old-price'><span class='reduce'><?=$good['price'].' грн'?></span></div>
                <div class = 'new-price'><span id='hot-price'><?=$good['newprice'].' грн'?></span></div>
                <?php endif;?>
              </div>
    
              <div class='order'><?=$good['status']?></div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
    </div>  
  </div>

<?php include ROOT . '/views/maquette/footer.php'; ?>