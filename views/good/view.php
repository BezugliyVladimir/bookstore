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
  <div id="box-main">
    <div class="case">
      <div class="main-content"><!-- main content area-->
        <div class="card-goods"><!--good-details-->
          <div class = "card-title">
            <span id="good-title"><?=$good['title']; ?></span>
          </div>
          <div class="row-card clearfix">
            <div class="col-image">
              <div class="image-goods">
                  <img id = "image-good" src='<?=$good['path']?>' alt='' border='0'/>
                
                  <?php if ($good['is_discount']):?>
                    <div id="discount-big"><img src='\template\picture\icon\discount2.png'/></div>
                  <?php endif;?>
                  <?php if ($good['is_special']):?>
                    <div class="hot"><img src='\template\picture\icon\special.png'/></div>
                  <?php endif;?>
              </div>
            </div>
            <div class = "col-information">
              <div class="information-goods">
                <span id = "code">Код товара: <?php echo $good['code']; ?></span>
                <div class='card-price clearfix'>
                  <div class='card-price'>
                   <?php if (!$good['newprice']):?>          
                    <div><span class='price big-price'><?=$good['price'].' грн'?></span></div>
                   <?php  else:?>
                    <div class = 'old-price'><span class='reduce big'><?=$good['price'].' грн'?></span></div>
                    <div class = 'new-price big-price'><span id='hot-price'><?=$good['newprice'].' грн'?></span></div>
                   <?php endif;?>
                    <div><span id='status'><?=$good['is_available'] ?></span></div>
                  </div>
                 <?php if($good['available']):?>
                  <div class='card-order'><?=$good['status']?></div>
                 <?php endif;?>
                </div>
                <div class = "details">
                  <table id="details">
                    <tbody>
                    <tr class='string'>
                      <td><div class='border-dotted'><div>Автор:</div></div></td>
                      <td class='value'><?=$good['author']?></td>
                    </tr>
                    <tr class='string'>
                      <td><div class='border-dotted'><div>Издательство:</div></div></td>
                      <td class = 'value'><?=$good['publish']?></td>
                    </tr>
                    <tr class='string'>
                      <td><div class='border-dotted'><div>Язык:</div></div></td>
                      <td class = 'value'><?=$good['language']?></td>
                    </tr>
                    <tr class='string'>
                      <td><div class='border-dotted'><div>Год издательства:</div></div></td>
                      <td class = 'value'><?=$good['pubyear']?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-----------good-description------------->
          <div class="row-card">                                
            <br/>
            <div class = 'about-good'>Описание товара</div>
            <div id = 'description'><p><span><?php echo $good['description']; ?></span></p></div>
          </div>
        </div><!--/good-details-->   
      </div><!--/main content area-->
    </div>
  </div>  
</div>

<?php include ROOT . '/views/maquette/footer.php'; ?>