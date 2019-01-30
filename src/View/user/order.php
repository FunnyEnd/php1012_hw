<?php include 'header.php'; ?>
  <div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/basket">Basket</a></li>
      <li class="breadcrumb-item active">Order</li>
    </ol>
  </div>
<?php if (isset($error)) :?>
  <div class="text-center">
    <h5><?= $error; ?></h5>
  </div>
<?php endif; ?>
  <div class="row w-75 ml-auto mr-auto">
    <div class="col-12">
      <form action="/order" method="POST">
        <div class="form-group row">
          <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-sm" id="staticEmail" name="email" value="<?= $email; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="first-name" class="col-sm-2 col-form-label">First name</label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-sm" id="first-name" name="first-name" value="<?= $firstName; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="last-name" class="col-sm-2 col-form-label">Last name</label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-sm" id="last-name" name="last-name" value="<?= $lastName; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="phone" class="col-sm-2 col-form-label">Phone</label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-sm" id="phone" name="phone" value="<?= $phone; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="city" class="col-sm-2 col-form-label">City</label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-sm" id="city" name="city" value="<?= $city; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="stock" class="col-sm-2 col-form-label">Stock</label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-sm" id="stock" name="stock" value="<?= $stock; ?>">
          </div>
        </div>
        <div class="form-group row">
          <label for="comment" class="col-sm-2 col-form-label">Comment</label>
          <div class="col-sm-10">
            <textarea class="form-control form-control-sm" id="comment" name="comment" rows="3" placeholder="Beautiful!!!"><?= $comment; ?></textarea>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-sm-8"></div>
          <div class="col-sm-4">
            <button type="submit" class="btn w-100 btn-success">Confirm order</button>
          </div>
        </div>
      </form>
    </div>
  </div>

<?php include 'footer.php'; ?>