<?php include ROOT . '/views/maquette/header.php'; ?>

<section class='main'>
  <div class="container-register">
    <div class="row-register clearfix">
      <h2>Регистрация</h2>
        <div class="register">
          <div class="register-form"><!--register-form-->
            <?php if (!empty($errors)):?>
              <ul class = "errors">
              <?php foreach($errors as $error):?>
                <li> - <?=$error?></li>
              <?php endforeach;?>
              </ul>
            <?php endif;
                  if($result):
            ?>
            <p id='success-register'>Вы зарегистрированы!</p>
            <?php endif?>
            <form action="#" method="post">
              <div class = 'clearfix'>
                <input type="text" id = "name" class = "<?=$className ?>"name="name" placeholder="Имя" value="<?=$name; ?>" class = '<?=$classInput?>'>
                <span id="help1" class="help" style="position:absolute"></span>
              </div>
              <div class = 'clearfix'>
                <input type="email" id = "email" class = "<?=$classEmail ?>" name="email" placeholder="E-mail" value="<?=$email; ?>"/>
                <span id="help2" class="help" style="position:absolute"></span>
              </div>
              <div class = 'clearfix'>  
                <input type="password" id = "password" class = "<?=$classPassword ?>" name="password" placeholder="Пароль" value="<?=$password; ?>"/>
                <span id="help3" class="help" style="position:absolute"></span>
              </div> 
              <input type="submit" name="button" class="btn" value="Зарегистрироваться">
              </form>
          </div><!--/register-form-->
          <br/>
          <br/>
        </div>
    </div>
  </div>
</section>

<?php include ROOT . '/views/maquette/footer.php'; ?>