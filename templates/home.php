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
        <img class="d-block w-100" src="сomponents/images/header1.png" alt="Первый слайд">
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="сomponents/images/header1.png" alt="Второй слайд">
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="сomponents/images/header1.png" alt="Третий слайд">
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
  <?php foreach ($products as $p):
      if($p['cat'] == 1) :?>
  <div class="col-3">
    <div class="product-card">
      <a href="index.php?page=product&id=<?php echo $p['id'];?>"><img class="d-block w-100" src="<?php echo $p['image-path'];?>" alt=""></a>
      <div class="product-card-name">
        <h3><a href="index.php?page=product&id=<?php echo $p['id'];?>"><?php echo $p['name'];?></a></h3>
      </div>
      <div class="product-card-price text-danger">
        <span><?php echo $p['price'];?> UAH</span>
      </div>
      <div class="product-card-description">
      <?php foreach ($p['ch']['group1'] as $ch => $data):?>
        <p><?php echo $ch;?>: <?php echo $data;?></p>
      <?php endforeach;?>
      </div>
    </div>
  </div>
  <?php endif; endforeach; ?>
  </div>
</div>
<div class="category-header d-flex justify-content-center">
  <h2>Popular at category 1</h2>
</div>
<div class="row">
    <?php foreach ($products as $p):
        if($p['cat'] == 2) :?>
          <div class="col-3">
            <div class="product-card">
              <a href="index.php?page=product&id=<?php echo $p['id'];?>"><img class="d-block w-100" src="<?php echo $p['image-path'];?>" alt=""></a>
              <div class="product-card-name">
                <h3><a href="index.php?page=product&id=<?php echo $p['id'];?>"><?php echo $p['name'];?></a></h3>
              </div>
              <div class="product-card-price text-danger">
                <span><?php echo $p['price'];?> UAH</span>
              </div>
              <div class="product-card-description">
                  <?php foreach ($p['ch']['group1'] as $ch => $data):?>
                    <p><?php echo $ch;?>: <?php echo $data;?></p>
                  <?php endforeach;?>
              </div>
            </div>
          </div>
        <?php endif; endforeach; ?>
</div>
<div class="category-header d-flex justify-content-center">
  <h2>Popular at category 2</h2>
</div>
<div class="row">
    <?php foreach ($products as $p):
        if($p['cat'] == 3) :?>
          <div class="col-3">
            <div class="product-card">
              <a href="index.php?page=product&id=<?php echo $p['id'];?>"><img class="d-block w-100" src="<?php echo $p['image-path'];?>" alt=""></a>
              <div class="product-card-name">
                <h3><a href="index.php?page=product&id=<?php echo $p['id'];?>"><?php echo $p['name'];?></a></h3>
              </div>
              <div class="product-card-price text-danger">
                <span><?php echo $p['price'];?> UAH</span>
              </div>
              <div class="product-card-description">
                  <?php foreach ($p['ch']['group1'] as $ch => $data):?>
                    <p><?php echo $ch;?>: <?php echo $data;?></p>
                  <?php endforeach;?>
              </div>
            </div>
          </div>
        <?php endif; endforeach; ?>
</div>
<?php include 'footer.php';?>