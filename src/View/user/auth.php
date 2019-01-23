<?php include 'header.php'; ?>
  <div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active">Login</li>
    </ol>
  </div>
<?php if (!empty($erorr)): ?>
  <div><p><?= $error; ?></p></div>
<?php endif; ?>
  <div class="w-50 ml-auto mr-auto mt-5">
    <form action="/auth" method="post">
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
      <button class="btn btn-success w-100">Login</button>
    </form>
  </div>
<?php include 'footer.php'; ?>