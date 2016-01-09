<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> <?php echo ($edit_mode) ? 'Edit Category' : 'Add Category'; ?></h1>

        <div class="buttons">
            <a class="button" href="#" id="save" onClick="$('#trivia_categories_edit_form').submit()"><span>Save</span></a>
        </div>
    </div>
    <div class="content">
        <?php echo form_open(null, 'id="trivia_categories_edit_form"'); ?>

        <?php if ($edit_mode): ?>
            <div class="tabs">
                <ul class="htabs">
                    <li><a href="#edit-user-tab">Edit Category</a></li>
                </ul>
            <?php endif; ?>

            <div id="edit-user-tab">
                <div class="form" id="new_row">
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Category Name:', 'question'); ?>
                        <?php echo form_input(array('id' => 'name', 'name' => 'name', 'value' => set_value('name', (isset($categories->name)) ? $categories->name : ''))); ?>
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
</script>
<?php js_end(); ?>