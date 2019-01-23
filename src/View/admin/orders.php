<?php include 'header.php' ?>
<?php include 'menu.php' ?>
  <table class="table">
    <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Email</th>
      <th scope="col">Price</th>
      <th scope="col">Status</th>
      <th scope="col">More</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order) : ?>
      <tr>
        <th scope="row"><?= $order->getId(); ?></th>
        <td><?= $order->getContactPerson()->getEmail(); ?></td>
        <td><?= $order->getPrice(); ?> UAH</td>
        <td><?= $order->getConfirmAsString(); ?></td>
        <td><button class="btn btn-info">More</button></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

<?php include 'footer.php' ?>