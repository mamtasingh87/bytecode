<div class="product-wrap">
<?php $i=0; ?>
<?php echo $this->session->flashdata('product_cart_message'); ?>

<h2 class="sub-inner-head">Products</h2>
<div class="category-select-wrap">
    <span class="select-span">
        <select name="category_list" id="category-select" class="form-control" onchange="fetchSelectedCategoryProduct(this.value)">
    <?php foreach ($categories as $categoryKey=>$categoryValue): ?>
    <option value="<?php echo $categoryKey?>" <?php echo ($categoryKey==$cat_id)?'selected="selected"':'' ?>><?php echo $categoryValue ?></option>
    <?php $i++; ?>
    <?php endforeach; ?>
</select>
    </span>
</div>
<div class="pagination">
    <div class="links"> <?php  echo $this->pagination->create_links();?> </div>
</div>
<div id="product-list-show" class="product-list-show-wrap">
    <div id="products" class=" list-group">
    <div class="row">
<?php if (!empty($products)): ?>    
        <?php foreach ($products as $contentValues): ?>
            <div class="item col-sm-4  col-lg-4">
                <?php echo form_open('redemption/redeem/productview'); ?>
                <input type="hidden" name="pid" value="<?php echo $contentValues['id'] ?>" />
                <input min="1" type="hidden"name="qty" value="1"/>
                <div class="thumbnail">
                <div class="prod-image">
                     <?php
                    $images = $this->load->model('products_model')->getProductImages($contentValues['id']);
                    if($images){
                        $image = '<img src="'.base_url().'uploads/product_images/'.$images[0]->pimage.'">';
                    } else {
                        $image = '<img src="'.base_url().'uploads/product_images/no_picture.png">';
                    }
                ?>
                    <a href="<?php echo site_url('redemption/redeem/productview/?pid=' . $contentValues['id']) ?>"> <?php echo $image; ?> </a>
                    </div>
                    
                    <div class="caption">
                        <h4 class="group list-group-item-heading">
                            <a href="<?php echo site_url('redemption/redeem/productview/?pid=' . $contentValues['id']) ?>"><?php echo $contentValues['name'] ?></a>
                        </h4>
                        <p class="group inner list-group-item-text">
                            <?php // echo $contentValues['description'] ?>
                        </p>
                        <?php if(isset($prices[$contentValues['id']])):?>
                        <div class="price-select">
                            <div class="form-group">
                            <span class="input-icon">
                                <select name="prices" class="form-control">
                            <?php foreach ($prices[$contentValues['id']] as $key => $value) {?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['price'];?></option>
                            <?php };?>
                            </select>
                            </span>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="btn-wrap">
                            <button value="submit" type="submit" class="blue  btn btn-primary"><i class="icon-cart"></i> Add to Cart</button>
<!--                            <a class="btn btn-primary pull-right" href="<?php // echo site_url('redemption/redeem/productview/?pid=' . $contentValues['id']) ?>">View</a>-->
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>        
        <?php endforeach; ?>
<?php else: ?>
    <h2><p style="color: rosybrown">No Product Available to show</p></h2>
<?php endif; ?>
</div>

    </div>
</div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(e){
        var catVal=jQuery('#category-select').val()
//        fetchSelectedCategoryProduct(catVal)
    })
    
    function fetchSelectedCategoryProduct(catid){
          window.location = '<?php echo $config['base_url']?>'+"?cat_id="+catid;
    }
    
</script>