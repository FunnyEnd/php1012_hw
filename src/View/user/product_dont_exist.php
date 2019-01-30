<?php include 'header.php'; ?>
<div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
  </ol>
</div>
<div class="text-center">
    <?php if (isset($error)) : ?>
      <h5><?= $error ?></h5>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
