<?php include 'header.php' ?>
<?php include 'menu.php' ?>
  <div class="d-flex justify-content-center">
    <ul class="pagination pagination-sm">
      <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/order/page/<?= ($currentPage - 1); ?>">&laquo;</a>
      </li>
        <?php for ($i = 0; $i < $countPages; $i++): ?>
          <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
            <a class="page-link"
               href="/admin/order/page/<?= ($i + 1); ?>"><?= $i + 1; ?></a>
          </li>
        <?php endfor; ?>
      <li class="page-item <?php if ($currentPage == $countPages) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/order/page/<?= ($currentPage + 1); ?>">&raquo;</a>
      </li>
    </ul>
  </div>
  <table class="table table-striped">
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
        <td><a href="/admin/order/<?= $order->getId(); ?>">More</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <div class="d-flex justify-content-center">
    <ul class="pagination pagination-sm">
      <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/order/page/<?= ($currentPage - 1); ?>">&laquo;</a>
      </li>
        <?php for ($i = 0; $i < $countPages; $i++): ?>
          <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
            <a class="page-link"
               href="/admin/order/page/<?= ($i + 1); ?>"><?= $i + 1; ?></a>
          </li>
        <?php endfor; ?>
      <li class="page-item <?php if ($currentPage == $countPages) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/order/page/<?= ($currentPage + 1); ?>">&raquo;</a>
      </li>
    </ul>
  </div>
<?php include 'footer.php' ?>