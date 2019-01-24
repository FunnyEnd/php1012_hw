<?php include 'header.php' ?>
<?php include 'menu.php' ?>
<h4>Order info</h4>
<div class="row">
  <div class="col-4">Order id</div>
  <div class="col-8"><?= $order->getId(); ?></div>
</div>
<div class="row">
  <div class="col-4">Confirm</div>
  <div class="col-8"><?= $order->getConfirmAsString(); ?>
    <?php if(!$order->getConfirm()) :?>
    <a href="/admin/order/<?= $order->getId(); ?>/confirm">Confirm</a>
    <?php endif; ?>
  </div>
</div>
<div class="row">
  <div class="col-4">First name</div>
  <div class="col-8"><?= $order->getContactPerson()->getFirstName(); ?></div>
</div>
<div class="row">
  <div class="col-4">Last name</div>
  <div class="col-8"><?= $order->getContactPerson()->getLastName(); ?></div>
</div>
<div class="row">
  <div class="col-4">Phone</div>
  <div class="col-8"><?= $order->getContactPerson()->getPhone(); ?></div>
</div>
<div class="row">
  <div class="col-4">Email</div>
  <div class="col-8"><?= $order->getContactPerson()->getEmail(); ?></div>
</div>
<div class="row">
  <div class="col-4">City</div>
  <div class="col-8"><?= $order->getContactPerson()->getCity(); ?></div>
</div>
<div class="row">
  <div class="col-4">Stock</div>
  <div class="col-8"><?= $order->getContactPerson()->getStock(); ?></div>
</div>
<div class="row">
  <div class="col-4">Time</div>
  <div class="col-8"><?= $order->getCreateAt()->format('Y-m-d H:i:s'); ?></div>
</div>
<div class="row">
  <div class="col-4">Full price</div>
  <div class="col-8"><?= $order->getPrice() ?></div>
</div>
<div class="row">
  <div class="col-4">Comment</div>
  <div class="col-8"><?= $order->getComment(); ?></div>
</div>
<h4>Order products</h4>

<table class="table table-striped">
  <thead class="thead-dark">
  <tr>
    <th scope="col">Product name</th>
    <th scope="col">Count</th>
    <th scope="col">Full price</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($orderProducts as $orderProduct) : ?>
    <tr>
      <td><?= $orderProduct->getProduct()->getTitle(); ?></td>
      <td><?= $orderProduct->getCount(); ?></td>
      <td><?= $orderProduct->getFullPrice(); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php include 'footer.php' ?>
