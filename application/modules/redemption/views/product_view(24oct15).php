<?php if (!empty($product_detail)): ?>
    <div class="product-view">
        <div class="product-view-top">
            <div class="productExcerpt">
                <h2 class="product-name sub-inner-head"><?php echo $product_detail['name'] ?></h2>
            </div>            
            <div class="row">
                <div data-medium-cols="2/5" data-cols="2/5" class="col-sm-6">                
                    <div class="productImg">
                        <?php
                        $images = $this->load->model('products_model')->getProductImages($product_detail['id']);
                        if ($images) {
                            foreach ($images as $image) {
                                echo $image = '<img src="' . base_url() . 'uploads/product_images/' . $image->pimage . '">';
                            }
                        } else {
                            echo $image = '<img src="' . base_url() . 'uploads/product_images/no_picture.png">';
                        }
                        ?>
                    </div>
                </div>
                <div data-medium-cols="3/5" data-cols="3/5" class="col-sm-6">
                    <div class="productDetails">            
                        <div id="productAlerts"></div>
<!--                        <div class="productPrice">
                            <?php // if ($product_detail['saleprice'] != 0.00) { ?>
                                <label class="sale">Price:</label>
                                <span class="sale-price">$<?php // echo (!empty($product_detail['saleprice'])) ? $product_detail['saleprice'] : ''; ?></span>
                                <strike>$<?php // echo (!empty($product_detail['price'])) ? $product_detail['price'] : ''; ?></strike>
                            <?php // } else { ?>
                                <label class="sale">Price:</label>
                                <span class="sale-price">$<?php // echo (!empty($product_detail['price'])) ? $product_detail['price'] : ''; ?></span>
                            <?php // } ?>
                        </div>           -->

                        <div class="productExcerpt">
                            <label>SKU:</label>
                            <?php echo $product_detail['slug'] ?>
                        </div>

                        <form accept-charset="utf-8" method="post" id="add-to-cart" action="<?php site_url('redemption/redeem/productview') ?>">
                            <input type="hidden" value="" name="cartkey">
                            <input type="hidden" value="<?php echo $product_detail['id'] ?>" name="pid">
                            <div class="quantity">
                                <label for="quantity">Qty: </label> 
                                <div class="input-box"><input min="1" type="number" id="quantity" name="qty" value="1"  class="form-control"/></div>
        <!--                        <style>#quantity { padding:5px; width:35px; border: 1px solid #555; }</style>-->
                            </div>
                            <?php if($prices&&  count($prices)):?>
                                 <div class="view-price">
                                 <div class="form-group">
                                <label for="prices">Price: </label> 
                            <span class="input-icon">
                                <select name="prices" class="form-control">
                                    <?php foreach ($prices as $key => $value) { ?>
                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['price'] ?></option>
                                    <?php } ?>
                                </select>
                            </span>
                            </div>
                            </div>
                            <?php endif;?>
                            <div class="add-cart-btn">
                                <button value="submit" type="submit" class="blue  btn btn-primary"><i class="icon-cart"></i> Add to Cart</button>
                            </div>
                        </form>               
                    </div>
                </div>   
            </div>
        </div>
        <div class="productDescription">
            <h3>Description</h3>
            <p> <?php echo $product_detail['description'] ?></p>
        </div>
    </div>               
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function() {
        $(".productImg").owlCarousel({
            navigation: true, // Show next and prev buttons
            slideSpeed: 300,
            paginationSpeed: 400,
            autoPlay: 3000,
            singleItem: true
        });
    });
</script>
