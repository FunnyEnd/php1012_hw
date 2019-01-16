<?php include 'header.php'; ?>
  <div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active">Register</li>
    </ol>
  </div>
<?php if (!empty($erorr)): ?>
  <div><p><?= $error; ?></p></div>
<?php endif; ?>
  <div class="w-50 ml-auto mr-auto mt-5">
    <form action="/register" method="post">
      <div class="form-group row">
        <label for="email" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="email" name="email" placeholder="semo@email.com"
                 value="<?= $email ?>"
                 required>
        </div>
      </div>
      <div class="form-group row">
        <label for="password" class="col-sm-3 col-form-label">Password</label>
        <div class="col-sm-9">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                 value="<?= $password ?>"
                 required>
        </div>
      </div>
      <div class="form-group row">
        <label for="first-name" class="col-sm-3 col-form-label">First Name</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="first-name" name="first-name" placeholder="Alex"
                 value="<?= $firstName ?>" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="last-name" class="col-sm-3 col-form-label">Last Name</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="last-name" name="last-name" placeholder="Korneinko"
                 value="<?= $lastName ?>"
                 required>
        </div>
      </div>
      <div class="form-group row">
        <label for="phone" class="col-sm-3 col-form-label">Phone</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="phone" name="phone" placeholder="0995402340"
                 value="<?= $phone ?>"
                 required>
        </div>
      </div>
      <div class="form-group row">
        <label for="city" class="col-sm-3 col-form-label">City</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="city" name="city" placeholder="Kharkov"
                 value="<?= $city ?>"
                 required>
        </div>
      </div>
      <div class="form-group row">
        <label for="stock" class="col-sm-3 col-form-label">Stock</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="stock" name="stock" placeholder="Stock #1"
                 value="<?= $stock ?>"
                 required>
        </div>
      </div>
      <button class="btn btn-success w-100">Register</button>
    </form>
  </div>
<?php include 'footer.php'; ?>