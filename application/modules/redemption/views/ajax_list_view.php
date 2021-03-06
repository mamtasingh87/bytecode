<?php if (!empty($content)): ?>    
        <?php foreach ($content as $contentValues): ?>
            <div class="item  col-xs-4 col-lg-4">
                <?php echo form_open('redemption/redeem/productview'); ?>
                <input type="hidden" name="pid" value="<?php echo $contentValues['id'] ?>" />
                <input min="1" type="hidden"name="qty" value="1"/>
                <div class="thumbnail">
                     <?php
                    $images = $this->load->model('products_model')->getProductImages($contentValues['id']);
                    $priceModel = $this->load->model('product_price');
                    $prices=$priceModel->getProductsPrice($contentValues['id']);
                    if($images){
                        $image = '<img src="'.base_url().'uploads/product_images/'.$images[0]->pimage.'">';
                    } else {
                        $image = '<img src="'.base_url().'uploads/product_images/no_picture.png">';
                    }
                ?>
                    <?php echo $image; ?>
                    
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
           <!--                 <a class="btn btn-primary pull-right" href="<?php echo site_url('redemption/redeem/productview/?pid=' . $contentValues['id']) ?>">View</a>-->
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>        
        <?php endforeach; ?>
<?php else: ?>
    <h2><p style="color: rosybrown">No Product Available to show</p></h2>
<?php endif; ?>
