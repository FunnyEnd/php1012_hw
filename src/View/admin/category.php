<?php include 'header.php' ?>
<?php include 'menu.php' ?>
  <h4>Create new category</h4>
  <div>
    <form action="/admin/category" method="POST">
      <p><span>Category name: </span><input type="text" name="title"></p>
      <p>
        <button type="submit" class="btn btn-sm btn-info">Create</button>
      </p>
    </form>
  </div>
  <h4>Category list</h4>
  <div class="d-flex justify-content-center">
    <ul class="pagination pagination-sm">
      <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/category/page/<?= ($currentPage - 1); ?>">&laquo;</a>
      </li>
        <?php for ($i = 0; $i < $countPages; $i++): ?>
          <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
            <a class="page-link"
               href="/admin/category/page/<?= ($i + 1); ?>"><?= $i + 1; ?></a>
          </li>
        <?php endfor; ?>
      <li class="page-item <?php if ($currentPage == $countPages) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/category/page/<?= ($currentPage + 1); ?>">&raquo;</a>
      </li>
    </ul>
  </div>
  <table class="table table-striped">
    <thead class="thead-dark">
    <tr>
      <th scope="col">Title</th>
      <th scope="col">Update</th>
      <th scope="col">Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($category as $cat) : ?>
      <tr>
        <form action="/admin/category/<?= $cat->getId(); ?>" method="POST">
          <input type="hidden" name="__method" value="PUT" />
          <td scope="row"><input type="text" name="title" value="<?= $cat->getTitle(); ?>"/></td>
          <td scope="row">
            <button type="submit" class="btn btn-sm btn-info">Update</button>
          </td>
        </form>
        <form action="/admin/category/<?= $cat->getId(); ?>" method="POST">
          <input type="hidden" name="__method" value="DELETE" />
          <td scope="row">
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
          </td>
        </form>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <div class="d-flex justify-content-center">
    <ul class="pagination pagination-sm">
      <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/category/page/<?= ($currentPage - 1); ?>">&laquo;</a>
      </li>
        <?php for ($i = 0; $i < $countPages; $i++): ?>
          <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
            <a class="page-link"
               href="/admin/category/page/<?= ($i + 1); ?>"><?= $i + 1; ?></a>
          </li>
        <?php endfor; ?>
      <li class="page-item <?php if ($currentPage == $countPages) echo 'disabled'; ?>">
        <a class="page-link"
           href="/admin/category/page/<?= ($currentPage + 1); ?>">&raquo;</a>
      </li>
    </ul>
  </div>
<?php include 'footer.php' ?>