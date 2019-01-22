<?php include 'header.php'; ?>
<div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active"><?= $categoryCurrent->getTitle(); ?></li>
  </ol>
</div>
<div class="row">
  <div class="col-9">
    <div class="d-flex justify-content-center">
      <ul class="pagination pagination-sm">
        <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
          <a class="page-link"
             href="<?= $pagination['part1'] . ($currentPage - 1) . $pagination['part2']; ?>">&laquo;</a>
        </li>
          <?php for ($i = 0; $i < $pagesCount; $i++): ?>
            <li class="page-item <?php if ($currentPage == $i + 1) echo 'active'; ?>">
              <a class="page-link"
                 href="<?= $pagination['part1'] . ($i + 1) . $pagination['part2']; ?>"><?= $i + 1; ?></a>
            </li>
          <?php endfor; ?>
        <li class="page-item <?php if ($currentPage == $pagesCount) echo 'disabled'; ?>">
          <a class="page-link"
             href="<?= $pagination['part1'] . ($currentPage + 1) . $pagination['part2']; ?>">&raquo;</a>
        </li>
      </ul>
    </div>
    <div class="row">
        <?php foreach ($products as $product): ?>
          <div class="col-4">
            <div class="product-card">
              <a href="/product/<?= $product->getId(); ?>"><img class="d-block w-100"
                                                                src="/<?= $product->getImage()->getPath(); ?>"
                                                                alt=""></a>
              <div class="product-card-name">
                <h3><a href="/product/<?= $product->getId(); ?>"><?= $product->getTitle(); ?></a></h3>
              </div>
              <div class="product-card-price text-danger">
                <span><?= $product->getPriceAtBills(); ?> UAH</span>
              </div>
              <div class="product-card-description">
                <p>asdasd: asdasda</p>
                <p>asdasd: asdasda</p>
                <p>asdasd: asdasda</p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

    </div>
    <!-- paginatin-->
  </div>
  <aside class="col-3">
    <div class="aside-header">
      <p>Filter</p>
    </div>
    <div class="filter">
      <form action="/" method="get">
          <?php foreach ($characteristics as $characteristic): ?>
            <div class="filter-header">
              <p><?= $characteristic['info']->getTitle(); ?></p>
            </div>
              <?php foreach ($characteristic['values'] as $value): ?>
              <div class="form-check">
                <input class="form-check-input"
                       type="checkbox" <?php if ($value['selected'] !== 0) echo "checked=\"checked\""; ?>
                       name="filter<?= $characteristic['info']->getId(); ?>"
                       value="<?= $value['value']; ?>">
                <label class="form-check-label"><?= $value['value']; ?></label>
              </div>
              <? endforeach; ?>
          <?php endforeach; ?>
        <button type="submit" class="mt-2 d-block w-100 btn" id="filter-button">Show</button>
      </form>
    </div>
  </aside>
</div>
<script>
    window.categoryId = <?= $categoryCurrent->getId(); ?>;
    $(document).ready(function () {
        $('#filter-button').click(function (event) {
            event.preventDefault();
            var arr = [];

            $('input[name^="filter"]').each(function (index, el) {
                if (($(el).prop('checked'))) {
                    var cat = $(el).attr('name').substr(6);
                    var val = $(el).val();
                    if (!Array.isArray(arr[cat])) {
                        arr[cat] = [];
                    }
                    arr[cat].push(val);
                }
            });

            var filterString = '';
            var index = 0;
            arr.forEach(function (item, i, arr) {
                if (index > 0) {
                    filterString += ';'
                }
                filterString += i + "=" + item;
                index++;
            });

            window.location.href = "/category/" + window.categoryId + "/page/1/filter/" + filterString;
        });
    });
</script>
<?php include 'footer.php'; ?>
