<?php include ROOT . '/views/maquette/header.php'; ?>

<section>
  <div class="container-account">
    <div class="row-account">
      <h2>Кабинет пользователя</h2>
      <h4>Привет, <?php echo $userData['name'];?>!</h4>
      <ul>
          <li><a href="/account/change">Редактировать данные</a></li>
      </ul>
    </div>
  </div>
</section>

<?php include ROOT . '/views/maquette/footer.php'; ?>