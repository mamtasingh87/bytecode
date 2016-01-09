<?php $i=0; ?>
<?php echo $this->session->flashdata('product_cart_message'); ?>

<h2 class="sub-inner-head">Products</h2>
<div class="category-select-wrap">
<select name="category_list" id="category-select" class="form-control" onchange="fetchSelectedCategoryProduct(this.value)">
    <?php foreach ($categories as $categoryKey=>$categoryValue): ?>
    <option value="<?php echo $categoryKey?>" <?php echo ($i==0)?'selected="selected"':'' ?>><?php echo $categoryValue ?></option>
    <?php $i++; ?>
    <?php endforeach; ?>
</select>
</div>
<div class="pagination">
    <div class="links"> <?php echo $this->ajax_pagination->create_links(); ?> </div>
</div>
<div id="product-list-show" class="product-list-show-wrap">
    
</div>

<script type="text/javascript">
    jQuery(document).ready(function(e){
        var catVal=jQuery('#category-select').val()
        fetchSelectedCategoryProduct(catVal)
    })
    
    function fetchSelectedCategoryProduct(catid){
        jQuery.ajax({
            url:'<?php echo site_url('redemption/redeem/showproduct') ?>',
            data:{cat_id:catid},
            success:function(evt){
                jQuery('#product-list-show').html(evt)
            }
        })
    }
    
</script>