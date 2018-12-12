<!DOCTYPE html>
<html lang="en">
<head>
  <title>OnlineShop.com</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="/Components/styles/main.css">
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
            <?php foreach ($category as $c):?>
            <a class="dropdown-item" href="/category/<?php echo $c['id'];?>"><?php echo $c['name'];?></a>
            <?php endforeach;?>
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
        <a class="nav-link" href="#">Cart <span class="badge badge-light badge-pill">0</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Login</a>
      </li>
    </ul>
  </div>
</nav>
<main class="container">