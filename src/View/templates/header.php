<!DOCTYPE html>
<html lang="en">
<head>
  <title>OnlineShop.com</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="/public/styles/main.css">

  <script src="/public/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark main-nav">
  <div class="container">
    <a class="navbar-brand" href="/">OnlineShop.com</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <div class="dropdown">
          <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
            Catalog
          </button>
          <div class="dropdown-menu">

              <?php foreach ($category as $cat): ?>
                <a class="dropdown-item" href="/category/<?php echo $cat->getId(); ?>"><?= $cat->getTitle(); ?></a>
              <?php endforeach; ?>

          </div>
        </div>
      </li>
    </ul>
    <ul class="nav navbar-nav mx-auto">
      <li>
        <form>
          <div class="form-row">
            <div class="col-10">
              <input type="text" class="form-control" placeholder="Product">
            </div>
            <div class="col-2">
              <button type="submit" class="btn btn-light">Search</button>
            </div>
          </div>
        </form>
      </li>
    </ul>
    <ul class="nav navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/basket">Basket <span class="badge badge-light badge-pill">0</span></a>
      </li>
        <?php if (!$auth): ?>
          <li class="nav-item">
            <a class="nav-link" href="/auth">Log In</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/register">Register</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/logout">Log Out</a>
          </li>
        <?php endif; ?>
    </ul>
  </div>
</nav>
<main class="container">