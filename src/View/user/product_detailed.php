<?php include 'header.php'; ?>
<!-- Modal -->
<div class="modal fade" id="basket-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Basket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal-body-text">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Continue shopping</button>
        <a href="/basket" class="btn btn-primary">Go to basket</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal end-->
<div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a
          href="/category/<?= $product->getCategory()->getId(); ?>"><?= $product->getCategory()->getTitle(); ?></a></li>
    <li class="breadcrumb-item active"><?= $product->getTitle(); ?></li>
  </ol>
</div>
<div class="row">
  <div class="col-7">
    <div class="product-detailed-image">
      <img class="w-100" src="/<?= $product->getImage()->getPath(); ?>" alt="">
    </div>
  </div>
  <div class="col-5 d-flex align-self-center flex-column bd-highlight text-center">
    <div class="product-detailed-name bd-highlight">
      <h3><?= $product->getTitle(); ?></h3>
    </div>
    <div class="product-detailed-price text-danger bd-highlight">
      <p><?= $product->getPriceAtBills(); ?> UAH</p>
    </div>
    <div class="bd-highlight">
        <?php if ($product->getAvailability() > 0) :?>
        <form class="form-group" action="/" id="add-product-to-basket-form">
          <div class="product-detailed-count">
            <input class="form-control w-50 ml-auto mr-auto" type="number" name="count" value="1">
            <input type="hidden" name="id" value="<?= $product->getId(); ?>">
          </div>
          <div class="product-detailed-buy mt-3">
            <button type="button" id="buy_button" class="btn btn-success w-50 ml-auto mr-auto">Buy</button>
          </div>
        </form>
        <?php else :?>
          <form class="form-group" action="/" id="add-product-to-basket-form">
            <div class="product-detailed-count">
              <input class="form-control w-50 ml-auto mr-auto" type="number" name="count" value="0" disabled>
              <input type="hidden" name="id" value="<?= $product->getId(); ?>">
            </div>
            <div class="product-detailed-buy mt-3">
              <button type="button" id="buy_button" class="btn btn-danger w-50 ml-auto mr-auto" disabled>Buy</button>
            </div>
          </form>
        <?php endif; ?>
    </div>

  </div>
</div>
<div class="mt-3">
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active show" data-toggle="tab" href="#description">Description</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#characteristic">Сharacteristic</a>
    </li>
  </ul>
  <div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active show p-2 text-justify" id="description">
      <p><?= $product->getDescription(); ?></p>
    </div>
    <div class="tab-pane fade" id="characteristic">
      <div class="product-detailed-characteristic w-50 ml-auto mr-auto mt-3">
        <?php foreach ($characteristics as $c) :?>
          <div class="row ch-row">
            <div class="col-6 ch-name"><?= $c->getCharacteristic()->getTitle();?></div>
            <div class="col-6 ch-data"><?= $c->getValue();?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<script>
    $("#buy_button").on('click', function () {
        var formData = $('#add-product-to-basket-form').serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        $.ajax({
            url: "/basket",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (result) {
                if (result.success) {
                    $('#modal-body-text').html('Product successfully added to cart!');
                    $('#basket-modal').modal('show');
                    $("#countProductsAtUserBasket").html(result.countProductsAtUserBasket);
                } else {
                    let error = result.error;
                    $('#modal-body-text').html(error);
                    $('#basket-modal').modal('show');
                }
            },
            error: function (result) {
                console.log(result);
            }
        });
    })
</script>
<?php include 'footer.php'; ?>
