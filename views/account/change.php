<?php include ROOT . '/views/maquette/header.php'; ?>

<section>
  <div class="container-change">
    <div class="row-change clearfix">
      <div class="change-user-data">
        <h2>Редактирование данных</h2>
        <?php if ($result): ?>
        <p>Данные отредактированы!</p>
        <?php else: ?>
          <?php if (isset($errors) && is_array($errors)): ?>
            <ul class = 'errors'>
                <?php foreach ($errors as $error): ?>
                    <li> - <?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <div class="log-on-form">
            <form action="#" method="post">
              <div class = "clearfix">
                <input type="text" name="name" placeholder="Имя" value="<?php echo $name; ?>"/>
              </div>
              <div class = "clearfix">
                <input type="password" name="password" placeholder="Пароль" value="<?php  ?>"/>
              </div>
              <input type="submit" name="submit" class="btn btn-default" value="Сохранить" />
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php include ROOT . '/views/maquette/footer.php'; ?>