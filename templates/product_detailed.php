<?php include 'header.php'; ?>
<div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo SITE_ROOT;?>у">Home</a></li>
        <li class="breadcrumb-item"><a href="index.php?page=cat&id=<?php echo $cur_cat['id'];?>"><?php echo $cur_cat['name'];?></a></li>
        <li class="breadcrumb-item active"><?php echo $cur_prod['name'];?></li>
    </ol>
</div>
<div class="row">
    <div class="col-7">
        <div class="product-detailed-image">
            <img class="w-100" src="<?php echo $cur_prod['image-path'];?>" alt="">
        </div>
    </div>
    <div class="col-5 d-flex align-self-center flex-column bd-highlight text-center">
        <div class="product-detailed-name bd-highlight">
            <h3><?php echo $cur_prod['name'];?></h3>
        </div>
        <div class="product-detailed-price text-danger bd-highlight">
            <p><?php echo $cur_prod['price'];?> UAH</p>
        </div>
        <div class="bd-highlight">
            <form class="form-group" action="/">
                <div class="product-detailed-count">
                    <input class="form-control w-50 ml-auto mr-auto" type="number" value="1">
                </div>
                <div class="product-detailed-buy mt-3">
                    <button type="submit" class="btn btn-success w-50 ml-auto mr-auto">Buy</button>
                </div>
            </form>
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
            <p><?php echo $cur_prod['description'];?></p>
        </div>
        <div class="tab-pane fade" id="characteristic">
            <div class="product-detailed-characteristic w-50 ml-auto mr-auto mt-3">
                <?php foreach ($cur_prod['ch'] as $chName => $ch):?>
                    <div class="text-center ch-row-group">
                        <span><?php echo $chName;?></span>
                    </div>
                    <?php foreach ($ch as $field => $data):?>
                    <div class="row ch-row">
                        <div class="col-6 ch-name"><?php echo $field; ?>: </div>
                        <div class="col-6 ch-data"><?php echo $data; ?></div>
                    </div>
                    <?php endforeach;?>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>
