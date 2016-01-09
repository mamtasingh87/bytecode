<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> <?php echo ($edit_mode) ? 'Edit Product' : 'Add Product'; ?></h1>

        <div class="buttons">
            <a class="button" href="#" id="save" onClick="$('#redemption_product_edit_form').submit()"><span>Save</span></a>
        </div>
    </div>
    <div class="content">
        <?php echo form_open_multipart(null, 'id="redemption_product_edit_form" enctype="multipart/form-data"'); ?>

        <div class="tabs">
            <ul class="htabs">
                <li><a href="#edit-product-tab"><?php echo ($edit_mode) ? 'Edit Product' : 'Add Product'; ?></a></li>
                <li><a href="#categories-tab">Select categories <span class="required">*</span></a></li>
                <li><a href="#images-tab">Add Images</a></li>
                <li><a href="#price-tab">Add Price <span class="required">*</span></a></li>
            </ul>

            <div id="edit-product-tab">
                <div class="form">
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Name:', 'name'); ?>
                        <?php echo form_input(array('id' => 'name', 'name' => 'name', 'value' => set_value('name', (isset($product->name)) ? $product->name : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('SKU:', 'sku'); ?>
                        <?php echo form_input(array('id' => 'sku', 'name' => 'sku', 'value' => set_value('sku', (isset($product->sku)) ? $product->sku : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('Slug:', 'slug'); ?>
                        <?php echo form_input(array('id' => 'slug', 'name' => 'slug', 'value' => set_value('slug', (isset($product->slug)) ? $product->slug : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('Description:', 'description'); ?>
                        <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => set_value('description', (isset($product->description)) ? $product->description : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('Excerpt:', 'excerpt'); ?>
                        <?php echo form_textarea(array('id' => 'excerpt', 'name' => 'excerpt', 'value' => set_value('excerpt', (isset($product->excerpt)) ? $product->excerpt : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('Weight:', 'weight'); ?>
                        <?php echo form_input(array('id' => 'weight', 'name' => 'weight', 'value' => set_value('weight', (isset($product->weight)) ? $product->weight : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> In Stock :', 'in_stock'); ?>
                        <?php echo form_dropdown('in_stock', $in_stock, set_value('in_stock', (isset($product->in_stock)) ? $product->in_stock : 0), 'id="in_stock" class="long"'); ?>
                    </div>

                    <div class="field_spacing dev-quantity" <?php echo isset($product->quantity) && $product->quantity ? '' : 'style="display: none"' ?>>
                        <?php echo form_label('<span class="required">*</span> Quantity:', 'quantity'); ?>
                        <?php echo form_input(array('id' => 'quantity', 'name' => 'quantity', 'value' => set_value('quantity', (isset($product->quantity)) ? $product->quantity : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('SEO Title:', 'seo_title'); ?>
                        <?php echo form_textarea(array('id' => 'seo_title', 'name' => 'seo_title', 'value' => set_value('seo_title', (isset($product->seo_title)) ? $product->seo_title : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('Meta:', 'meta'); ?>
                        <?php echo form_textarea(array('id' => 'meta', 'name' => 'meta', 'value' => set_value('meta', (isset($product->meta)) ? $product->meta : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Status :', 'enabled'); ?>
                        <?php echo form_dropdown('enabled', $status, set_value('enabled', (isset($product->enabled)) ? $product->enabled : 0), 'id="enabled" class="long"'); ?>
                    </div>

                    <!--                    <div class="field_spacing">
                    <?php // echo form_label('<span class="required">*</span> Price:', 'price'); ?>
                    <?php // echo form_input(array('id' => 'price', 'name' => 'price', 'value' => set_value('price', (isset($product->price)) ? $product->price : ''))); ?>
                                        </div>
                    
                                        <div class="field_spacing">
                    <?php // echo form_label('Sale Price:', 'saleprice'); ?>
                    <?php // echo form_input(array('id' => 'saleprice', 'name' => 'saleprice', 'value' => set_value('saleprice', (isset($product->saleprice)) ? $product->saleprice : ''))); ?>
                                        </div>-->
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Order:', 'p_order'); ?>
                        <?php echo form_input(array('id' => 'p_order', 'name' => 'p_order', 'value' => set_value('p_order', (isset($product->p_order)) ? $product->p_order : ''))); ?>
                    </div>
                </div>
            </div>

            <div id="categories-tab">
                <div class="form">
                    <?php if (!empty($categories)) { ?>
                        <?php foreach ($categories as $category) { ?>
                            <div class="field_spacing">
                                <?php echo form_label($category['name']); ?>
                                <?php echo form_checkbox(array('class' => 'category', 'name' => 'categories[]', 'checked' => set_checkbox('categories[]', $category['id'], in_array($category['id'], $relation) ? TRUE : FALSE)), $category['id'], in_array($category['id'], $relation) ? TRUE : FALSE); ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No categories found.</p>
                    <?php } ?>
                </div>
            </div>

            <div id="images-tab">
                <?php
                if (isset($upload_errors) && $upload_errors) {
                    foreach ($upload_errors as $errorImg) {
                        echo $errorImg;
                        ?>
                        <?php
                    }
                }
                ?>
                <div class="form">
                    <div class="field_spacing dev-product-image-fields">
                        <?php echo form_label('Product Images:', 'product_images'); ?>
<?php echo form_upload('product_image[]', '', 'multiple', array('class' => 'dev_product_image')); ?>
                        <a class="button dev-add-image-button">Add more images</a>                       
                         <!--<span class="dev-publish">Publish? <?php echo form_checkbox(array('class' => 'is_published', 'name' => 'product_image[]')) ?></span>-->
                    </div>


                    <div class="dev-image-field" style="display:none">
                        <div class="field_spacing">
                            <?php echo form_label(); ?>
<?php echo form_upload('product_image[]', '', 'multiple', array('class' => 'dev_product_image')); ?>
                            <!--<span class="dev-publish">Publish? <?php echo form_checkbox(array('class' => 'is_published', 'name' => 'product_image[]')) ?></span>-->
                        </div>
                    </div>
                </div>
<?php if (!empty($images)) { ?>
                    <div class="existing_images" style="padding-top: 20px;">
                        <div class="box">
                            <div class="heading">
                                <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> Images</h1>
                            </div>
                            <div>
                                <?php
                                if ($images) {
                                    foreach ($images as $imageVal) {
                                        echo '<div style="padding:10px;width:100px;height:130px;float:left;"><img src="' . base_url() . 'uploads/product_images/' . $imageVal->pimage . '" width="100" height="100"><a class="button" href="' . site_url(ADMIN_PATH . '/redemption/products/delete-image/' . $imageVal->pimid . '/' . $product_id) . '" style="margin-left: 14px;">Delete</a></div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
<?php } ?>
            </div>
            <div id="price-tab">
                <div class="form dev-product-price-fields">
                        <?php if (isset($prices) && count($prices)): $i = 1;
                            foreach ($prices as $key => $value) {
                                $i++;
                                ?>
                            <div class="field_spacing">
                            <?php echo form_label('Price:', 'extraprice' . $i); ?>
                                <?php echo form_input(array('id' => 'extraprice' . $i, 'name' => 'extraprice[' . $value['id'] . ']', 'value' => $value['price'])); ?>
                                <a class="dev-add-price-delete glyphicon glyphicon-trash" data-id="<?php echo $value['id']; ?>" data-p-id="<?php echo ((isset($product->id)) ? $product->id : ''); ?>">Delete</a>
                            </div>
                        <?php } ?>
                        <div class="field_spacing">
                            <?php echo form_label(); ?>
                            <a class="button dev-add-price-button">Add more</a>
                        </div>
                        <?php else: ?>
                        <div class="field_spacing">
    <?php echo form_label('Price:', 'extraprice'); ?>
    <?php echo form_input(array('id' => 'extraprice', 'name' => 'extraprice[]', 'value' => '')); ?>
                            <a class="button dev-add-price-button">Add more</a>
<?php endif; ?>
                    </div>
                </div>

            </div>
            <div class="clear"></div>

<?php echo form_close(); ?>
        </div>
    </div>
</div>
<div class="dev-price-field" style="display:none">
    <div class="field_spacing">
        <label></label>
<?php // echo form_label();  ?>
<?php // echo form_input(array('id' => 'extraprice', 'name' => 'extraprice[]', 'value' => ''));  ?>
        <input type="text" id="extraprice" value="" name="extraprice[]">
        <span class="removebutton" onclick="$(this).parent('div').remove();">remove? </span>
    </div>
</div>

<?php js_start(); ?>
<script type="text/javascript">

    $(document).ready(function() {

<?php if (isset($upload_errors) && $upload_errors) { ?>
            $(".tabs").tabs({active: 2});
<?php } else { ?>
            $(".tabs").tabs();
<?php } ?>

        $('#in_stock').change(function() {
            var value = $(this).val();
            if (value == '1')
                $('.dev-quantity').show();
            else
                $('.dev-quantity').hide();
        });

        if ($('#in_stock').val() == '1')
            $('.dev-quantity').show();

        var image_upload_markup = $('.dev-image-field').html();
        var price_markup = $('.dev-price-field').html();

        $('.dev-add-image-button').click(function() {
            $('.dev-product-image-fields').append(image_upload_markup);
        });
        $('.dev-add-price-button').click(function() {
            $('.dev-product-price-fields').append(price_markup);
        });
        $('.dev-add-price-delete').click(function() {
            var id = $(this).attr('data-id'), $th = $(this);
            var pid = $(this).attr('data-p-id');
            jQuery.ajax({
                url: '<?php echo site_url(ADMIN_PATH . '/redemption/products/deleteproductprice') ?>',
                data: {id: id, p: pid},
                type: 'POST',
                dataType: 'json',
                success: function(r) {
                    if (r.success) {
                        $th.parent('div').remove();
                    } else {

                    }
                }
            });
        });

    });

</script>
<?php js_end(); ?>