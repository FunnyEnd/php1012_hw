<?php include 'header.php'; ?>
  <header>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100" src="/images/header1.png" alt="Первый слайд">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="/images/header1.png" alt="Второй слайд">
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="/images/header1.png" alt="Третий слайд">
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </header>
  <div class="category-header d-flex justify-content-center">
    <h2>Popular</h2>
  </div>
  <div class="row">
        <?php foreach ($categoryFirstProducts as $product) :?>
        <div class="col-3 mb-4">
          <div class="product-card">
            <a href="product/<?= $product->getId(); ?>"><img class="d-block w-100"
                                                             src="<?= $product->getImage()->getPath(); ?>" alt=""></a>
            <div class="product-card-name">
              <h3><a href="product/<?= $product->getId(); ?>"><?= $product->getTitle(); ?></a></h3>
            </div>
            <div class="product-card-price text-danger">
              <span><?= $product->getPriceAtBills(); ?> UAH</span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
  </div>
  </div>

<?php include 'footer.php'; ?>