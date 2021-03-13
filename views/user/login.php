<?php include ROOT . '/views/maquette/header.php'; ?>

<section>
  <div class="container-login">
    <div class="row-login clearfix">
    <h2>Вход на сайт</h2>
      <div class="login">
        <?php if (isset($errors) && is_array($errors)): ?>
          <ul>
            <?php foreach ($errors as $error): ?>
            <li> - <?php echo $error; ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <div class="log-on-form">
          <form action="#" method="post">
            <div class = "clearfix">
              <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>"/>
            </div>
            <div class = "clearfix">
              <input type="password" name="password" placeholder="Пароль" value="<?php echo $password; ?>"/>
            </div>
            <input type="submit" name="submit" class="btn" value="Войти" />
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include ROOT . '/views/maquette/footer.php'; ?>