<?php include 'header.php'; ?>
  <div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active">Cart</li>
    </ol>
  </div>
  <div class="row ml-0 mr-0">
    <div class="col-12 product-cart-header">
      <div class="row p-1">
        <div class="col-1">
          <p class="mt-2 mb-0">Number</p>
        </div>
        <div class="col-5">
          <p class="mt-2 mb-0">Name</p>
        </div>
        <div class="col-4">
          <div class="row">
            <div class="col-3">
              <p class="mt-2 mb-0">Count</p>
            </div>
            <div class="col-3 ">
              <p class="mt-2 mb-0">Price</p>
            </div>
            <div class="col-6">
              <p class="mt-2 mb-0">Update</p>
            </div>
          </div>
        </div>
        <div class="col-2">
          <p class="mt-2 mb-0">Delete</p>
        </div>
      </div>
    </div>
    <?php $i=1; foreach($basketProducts as $basketProduct):?>
    <div class="col-12 product-cart-row">
      <div class="row p-1">
        <div class="col-1">
          <p class="mt-2 mb-0">#<?=$i++;?></p>
        </div>
        <div class="col-5">
          <p class="mt-2 mb-0"><?= $basketProduct->getProduct()->getTitle();?></p>
        </div>
        <div class="form-group col-4 mb-0">
          <div class="row">
            <div class="col-3">
              <input class="form-control w-100" type="number" value="<?= $basketProduct->getCount();?>">
            </div>
            <div class="col-4 ">
              <p class="mt-2 mb-0"><?= $basketProduct->getPriceAtBills();?> UAH</p>
            </div>
            <div class="col-5">
              <button class="btn btn-warning w-100">Update</button>
            </div>
          </div>
        </div>
        <div class="col-2">
          <button class="btn btn-danger w-100">Delete</button>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <div class="col-12 product-cart-total-row">
      <div class="row p-1">
        <div class="col-7 text-right">
          <p class="mt-2 mb-0">Total price:</p>
        </div>
        <div class="col-2">
          <p class="mt-2 mb-0"><?= $totalPrice;?> UAH</p>
        </div>
        <div class="col-3">
          <button class="btn btn-success w-100">Confirm order</button>
        </div>
      </div>
    </div>
  </div>
<?php include 'footer.php'; ?>