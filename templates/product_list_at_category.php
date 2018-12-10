<?php include 'header.php'; ?>
<div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo SITE_ROOT;?>">Home</a></li>
        <li class="breadcrumb-item active"><?php echo $cur_cat['name'];?></li>
    </ol>
</div>
<div class="row">
    <div class="col-9">

        <div class="d-flex justify-content-center">
            <ul class="pagination pagination-sm">
                <li class="page-item disabled">
                    <a class="page-link" href="#">&laquo;</a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">4</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">5</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">&raquo;</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <?php foreach ($cur_cat_products as $p):?>
                    <div class="col-4">
                        <div class="product-card">
                            <a href="index.php?page=product&id=<?php echo $p['id'];?>"><img class="d-block w-100" src="<?php echo $p['image-path'];?>" alt=""></a>
                            <div class="product-card-name">
                                <h3><a href="index.php?page=product&id=<?php echo $p['id'];?>"><?php echo $p['name'];?></a></h3>
                            </div>
                            <div class="product-card-price text-danger">
                                <span><?php echo $p['price'];?> UAH</span>
                            </div>
                            <div class="product-card-description">
                                <?php foreach ($p['ch']['group1'] as $ch => $data):?>
                                    <p><?php echo $ch;?>: <?php echo $data;?></p>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

        </div>
        <div class="mt-4 d-flex  justify-content-center">
            <ul class="pagination pagination-sm">
                <li class="page-item disabled">
                    <a class="page-link" href="#">&laquo;</a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">4</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">5</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">&raquo;</a>
                </li>
            </ul>
        </div>
    </div>
    <aside class="col-3">
        <div class="aside-header">
            <p>Filter</p>
        </div>
        <div class="filter">
            <form>
                <div class="filter-header">
                    <p>Filter 1</p>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="filter-header">
                    <p>Filter 2</p>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="filter-header">
                    <p>Filter 3</p>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="filter-header">
                    <p>Filter 4</p>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" >
                    <label class="form-check-label" >
                        Default checkbox
                    </label>
                </div>
                <button type="submit" class="mt-2 d-block w-100 btn ">Show</button>
            </form>
        </div>
    </aside>
</div>
<?php include 'footer.php';?>
