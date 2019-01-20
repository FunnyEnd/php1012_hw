<?php include 'header.php'; ?>
<div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active">Search</li>
  </ol>
</div>
<div class="row">
  <div class="col-12">
    <div class="d-flex justify-content-center">
      <ul class="pagination pagination-sm">
        <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
          <a class="page-link"
             href="/search/<?= $searchRequest ?>/page/<?= $currentPage - 1; ?>">&laquo;</a>
        </li>
          <?php for ($i = 0; $i < $pagesCount; $i++): ?>
            <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
              <a class="page-link"
                 href="/search/<?= $searchRequest ?>/page/<?= $i + 1; ?>"><?= $i + 1; ?></a>
            </li>
          <?php endfor; ?>
        <li class="page-item <?php if ($currentPage == $pagesCount) echo 'disabled'; ?>">
          <a class="page-link"
             href="/search/<?= $searchRequest ?>/page/<?= $currentPage + 1; ?>">&raquo;</a>
        </li>
      </ul>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="row">
        <?php foreach ($products as $product): ?>
          <div class="col-3">
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
  </div>
</div>
<div class=" mt-4 row">
  <div class="col-12">
    <div class="d-flex justify-content-center">
      <ul class="pagination pagination-sm">
        <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
          <a class="page-link"
             href="/search/<?= $searchRequest ?>/page/<?= $currentPage - 1; ?>">&laquo;</a>
        </li>
          <?php for ($i = 0; $i < $pagesCount; $i++): ?>
            <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
              <a class="page-link"
                 href="/search/<?= $searchRequest ?>/page/<?= $i + 1; ?>"><?= $i + 1; ?></a>
            </li>
          <?php endfor; ?>
        <li class="page-item <?php if ($currentPage == $pagesCount) echo 'disabled'; ?>">
          <a class="page-link"
             href="/search/<?= $searchRequest ?>/page/<?= $currentPage + 1; ?>">&raquo;</a>
        </li>
      </ul>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
