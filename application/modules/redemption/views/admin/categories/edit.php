<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> <?php echo ($edit_mode) ? 'Edit Category' : 'Add Category'; ?></h1>

        <div class="buttons">
            <a class="button" href="#" id="save" onClick="$('#redemption_categories_edit_form').submit()"><span>Save</span></a>
        </div>
    </div>
    <div class="content">
        <?php echo form_open(null, 'id="redemption_categories_edit_form" enctype="multipart/form-data"'); ?>

        <?php if ($edit_mode): ?>
            <div class="tabs">
                <ul class="htabs">
                    <li><a href="#edit-user-tab">Edit Category</a></li>
                </ul>
            <?php endif; ?>

            <div id="edit-user-tab">
                <div class="form" id="new_row">
                    <!--                    <div id="category" class="field_spacing">
                    <?php echo form_label('<span class="required">*</span> Parent Category:', 'parent_id'); ?>
                    <?php echo form_dropdown('parent_id', $categories, set_value('parent_id', (isset($category_model->parent_id)) ? $category_model->parent_id : ''), 'id="parent_id" class="long'); ?>
                                        </div>-->
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Category Name:', 'name'); ?>
                        <?php echo form_input(array('id' => 'name', 'name' => 'name', 'value' => set_value('name', (isset($category_model->name)) ? $category_model->name : ''))); ?>
                    </div>
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Slug:', 'slug'); ?>
                        <?php echo form_input(array('id' => 'slug', 'name' => 'slug', 'value' => set_value('slug', (isset($category_model->slug)) ? $category_model->slug : ''))); ?>
                    </div>
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Description:', 'description'); ?>
                        <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => set_value('description', (isset($category_model->description)) ? $category_model->description : ''))); ?>
                    </div>
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Excerpt:', 'excerpt'); ?>
                        <?php echo form_textarea(array('id' => 'excerpt', 'name' => 'excerpt', 'value' => set_value('excerpt', (isset($category_model->excerpt)) ? $category_model->excerpt : ''))); ?>
                    </div>
                    <div class="field_spacing">
                        <?php echo form_label('Seo title:', 'seo_title'); ?>
                        <?php echo form_textarea(array('id' => 'seo_title', 'name' => 'seo_title', 'value' => set_value('seo_title', (isset($category_model->seo_title)) ? $category_model->seo_title : ''))); ?>
                    </div>
                    <div class="field_spacing">
                        <?php echo form_label('Meta:', 'meta'); ?>
                        <?php echo form_textarea(array('id' => 'meta', 'name' => 'meta', 'value' => set_value('meta', (isset($category_model->meta)) ? $category_model->meta : ''))); ?>
                    </div>
                    <div class="field_spacing">
                        <?php echo form_label('Image:', 'image'); ?>
                        <?php echo form_upload(array('id' => 'image_categories', 'name' => 'image_categories')); ?>
                        <?php if (isset($category_model->image_categories) && $category_model->image_categories) { ?>
                            <img src="<?php echo BASE_URL . "uploads/category_images/" . $category_model->image_categories; ?>" height="100" width="200">
                        <?php } ?>
                    </div>
                    <div>
                        <?php echo form_label('Enable Category:', 'enabled'); ?>
                        <span>
                            <?php echo form_radio(array('id' => 'status_enabled', 'name' => 'enabled', 'value' => '1', 'checked' => set_radio('enabled', '1', (isset($category_model->enabled) && $category_model->enabled) ? TRUE : TRUE))); ?>
                            <label for="status_enabled">Enabled</label>
                            <?php echo form_radio(array('id' => 'status_disabled', 'name' => 'enabled', 'value' => '0', 'checked' => set_radio('enabled', '0', (isset($category_model->enabled) && !$category_model->enabled) ? TRUE : FALSE))); ?> 
                            <label for="status_disabled">Disabled</label>
                        </span>
                    </div>

                </div>
            </div>


            <div class="clear"></div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php js_start(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".tabs").tabs();
    });
//    function getCategory(obj) {
//        $(obj).siblings('div').remove();
//        var val = $(obj).val();
//        var html;
//        $.ajax({
//            url: "<?php // echo site_url(ADMIN_PATH . '/redemption/categories/searchcategory');          ?>",
//            type: 'POST',
//            dataType: "json",
//            data: {catId: val},
//            success: function(res)
//            {
//                console.log(res);
//                if (res.success) {
//                    html = "<div><select name='parent_id[]' class='category' onchange='getCategory(this)'><option value=''></option>";
//                    $.each(res.result, function(index, value) {
//                        html += "<option value='" + index + "'>" + value + "</option>";
//                    });
//                    html += "</select></div>";
//                    $(obj).parent('div').append(html);
//                }
//            }
//        });
//    }

</script>
<?php js_end(); ?>