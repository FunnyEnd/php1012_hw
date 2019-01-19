<?php include 'header.php'; ?>
<div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active"><?= $categoryCurrent->getTitle(); ?></li>
  </ol>
</div>
<div class="row">
  <div class="col-9">
    <div class="d-flex justify-content-center">
      <ul class="pagination pagination-sm">
        <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
          <a class="page-link"
             href="/category/<?= $categoryCurrent->getId(); ?>/page/<?= $currentPage - 1; ?>">&laquo;</a>
        </li>
          <?php for ($i = 0; $i < $pagesCount; $i++): ?>
            <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
              <a class="page-link"
                 href="/category/<?= $categoryCurrent->getId(); ?>/page/<?= $i + 1; ?>"><?= $i + 1; ?></a>
            </li>
          <?php endfor; ?>
        <li class="page-item <?php if ($currentPage == $pagesCount) echo 'disabled'; ?>">
          <a class="page-link"
             href="/category/<?= $categoryCurrent->getId(); ?>/page/<?= $currentPage + 1; ?>">&raquo;</a>
        </li>
      </ul>
    </div>
    <div class="row">
        <?php foreach ($products as $product): ?>
          <div class="col-4">
            <div class="product-card">
              <a href="/product/<?= $product->getId(); ?>"><img class="d-block w-100"
                                                                src="/<?= $product->getImage()->getPath(); ?>"
                                                                alt=""></a>
              <div class="product-card-name">
                <h3><a href="/product/<?= $product->getId(); ?>"><?= $product->getTitle(); ?></a></h3>
              </div>
              <div class="product-card-price text-danger">
                <span><?= $product->getPriceAtBills(); ?> UAH</span>
              </div>
              <div class="product-card-description">
                <p>asdasd: asdasda</p>
                <p>asdasd: asdasda</p>
                <p>asdasd: asdasda</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

    </div>
    <div class="mt-4 d-flex justify-content-center">
      <ul class="pagination pagination-sm">
        <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
          <a class="page-link"
             href="/category/<?= $categoryCurrent->getId(); ?>/page/<?= $currentPage - 1; ?>">&laquo;</a>
        </li>
          <?php for ($i = 0; $i < $pagesCount; $i++): ?>
            <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
              <a class="page-link"
                 href="/category/<?= $categoryCurrent->getId(); ?>/page/<?= $i + 1; ?>"><?= $i + 1; ?></a>
            </li>
          <?php endfor; ?>
        <li class="page-item <?php if ($currentPage == $pagesCount) echo 'disabled'; ?>">
          <a class="page-link"
             href="/category/<?= $categoryCurrent->getId(); ?>/page/<?= $currentPage + 1; ?>">&raquo;</a>
        </li>
      </ul>
    </div>
  </div>
  <aside class="col-3">
    <div class="aside-header">
      <p>Filter</p>
    </div>
    <div class="filter">
      <form>
        <div class="filter-header">
          <p>Filter 1</p>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="filter-header">
          <p>Filter 2</p>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="filter-header">
          <p>Filter 3</p>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="filter-header">
          <p>Filter 4</p>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="">
          <label class="form-check-label">
            Default checkbox
          </label>
        </div>
        <button type="submit" class="mt-2 d-block w-100 btn ">Show</button>
      </form>
    </div>
  </aside>
</div>
<?php include 'footer.php'; ?>
