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
        <div class="col-7">
          <p class="mt-2 mb-0">Name</p>
        </div>
        <div class="col-2">
          <p class="mt-2 mb-0">Count</p>
        </div>
        <div class="col-2">
          <p class="mt-2 mb-0">Price</p>
        </div>
        <div class="col-1">
          <p class="mt-2 mb-0">Delete</p>
        </div>
      </div>
    </div>
      <?php foreach ($basketProducts as $basketProduct): ?>
        <div class="col-12 product-cart-row" id="bp<?= $basketProduct->getId(); ?>">
          <div class="row p-1">
            <div class="col-7">
              <p class="mt-2 mb-0"><?= $basketProduct->getProduct()->getTitle(); ?></p>
            </div>
            <div class="col-2">
              <input class="form-control w-100" data-bp="<?= $basketProduct->getId(); ?>" type="number" min="0"
                     value="<?= $basketProduct->getCount(); ?>">
            </div>
            <div class="col-2">
              <p class="mt-2 mb-0">
                <span class="product-price" data-bp="<?= $basketProduct->getId(); ?>">
                    <?= $basketProduct->getPriceAtBills(); ?></span> UAH
              </p>
            </div>
            <div class="col-1">
              <button class="btn btn-danger w-100 delete-button" data-bp="<?= $basketProduct->getId(); ?>">X</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <div class="col-12 product-cart-total-row">
      <div class="row p-1">
        <div class="col-7 text-right">
          <h5 class="mt-2 mb-0"><b>Total price:</b></h5>
        </div>
        <div class="col-2">
          <h5 class="mt-2 mb-0"><b><span id="basket-total-price"><?= $totalPrice; ?></span> UAH</b></h5>
        </div>
        <div class="col-3">
          <a class="btn btn-success w-100" href="/order">Confirm order</a>
        </div>
      </div>
    </div>
  </div>
  <script>
      $("input[type='number']").blur(function () {
          var id = $(this).attr('data-bp');
          var count = $('input[type="number"][data-bp="' + id + '"]').val();

          if (parseInt(count) <= 0) {
              alert("Not available value.");
          } else {
              $.ajax({
                  url: "/basket/" + id,
                  type: "PUT",
                  data: {"count": count},
                  dataType: "json",
                  success: function (result) {
                      console.log(result);
                      if (result.success) {
                          $('#basket-total-price').html(result.totalPrice);
                          $('.product-price[data-bp=\"' + id + '\"]').html(result.productTotalPrice);
                      }

                      if (result.auth === false)
                          $(location).attr('href', '/auth')
                  },
                  error: function (data) {
                      console.log(data);
                  }
              });
          }
      });

      $(".delete-button").click(function () {
          console.log($(this).attr('data-bp'));
          var id = $(this).attr('data-bp');
          $.ajax({
              url: "/basket/" + id,
              type: "DELETE",
              dataType: "json",
              success: function (result) {
                  console.log(result);
                  if (result.success) {
                      $('#basket-total-price').html(result.totalPrice);
                      $('#bp' + id).hide(100);
                      $("#countProductsAtUserBasket").html(result.countProductsAtUserBasket);
                  }

                  if (result.auth === false)
                      $(location).attr('href', '/auth')
              },
              error: function (data) {
                  console.log(data);
              }
          });
      });
  </script>
<?php include 'footer.php'; ?>