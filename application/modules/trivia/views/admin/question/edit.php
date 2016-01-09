<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> <?php echo ($edit_mode) ? 'Edit Question' : 'Add Question'; ?></h1>

        <div class="buttons">
            <a class="button" href="#" id="save" onClick="$('#trivia_question_edit_form').submit()"><span>Save</span></a>
            <a class="button" href="#" id="add_new_option" onClick="addNewField()"><span>Add Option</span></a>
        </div>
    </div>
    <div class="content">
        <?php echo form_open(null, 'id="trivia_question_edit_form"'); ?>

        <?php
        if ($edit_mode):
            $label = TRUE;
            ?>
            <div class="tabs">
                <ul class="htabs">
                    <li><a href="#edit-user-tab">Edit Question</a></li>
                </ul>
            <?php endif; ?>

            <div id="edit-user-tab">
                <div class="form" id="new_row">
                    <div id="theme_layout_div">
                        <?php echo form_label('<span class="required">*</span> Category for:', 'cat_id'); ?>
                        <?php echo form_dropdown('categories', $categories, set_value('categories', (isset($Question->cat_id)) ? $Question->cat_id : '')); ?>
                    </div>
                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> Question:', 'question'); ?>
                        <?php echo form_input(array('id' => 'question', 'name' => 'question', 'value' => set_value('question', (isset($Question->question)) ? $Question->question : ''))); ?>
                    </div>

                    <div class="field_spacing">
                        <?php echo form_label('<span class="required">*</span> For Date:', 'date_on'); ?>
                        <?php echo form_input(array('id' => 'date_on', 'class' => 'datetime', 'name' => 'date_on', 'value' => set_value('date_on', !empty($Question->date_on) ? date(DATE_TIME_FORMAT, strtotime($Question->date_on)) : date('Y-m-d h:i:s a')))); ?>
                    </div>


                    <?php if (!$edit_mode): ?>

                        <div class="field_spacing">
                            <?php echo form_label('<span class="required">*</span>Options:', 'answers'); ?>
                            <span>
                                <?php echo form_radio(array('id' => 'radio_ans', 'name' => 'answers[]', 'value' => '$(this).sibling(input[type=text]).val()', 'checked' => set_radio('answers'))); ?>
                                <?php echo form_input(array('onblur' => 'addValueToRadio(this,$(this).val())', 'id' => 'radio_ans_text', 'name' => 'ans_text[]', 'value' => set_value('question', ''))); ?>
                            </span>
                        </div>
                        <div class="field_spacing">
                            <label></label>
                            <span>
                                <?php echo form_radio(array('name' => 'answers[]', 'value' => '', 'checked' => set_radio('answers'))); ?>
                                <?php echo form_input(array('onblur' => 'addValueToRadio(this,$(this).val())', 'name' => 'ans_text[]', 'value' => set_value('question', ''))); ?>
                            </span>
                        </div>

                    <?php else: ?>
                        <?php foreach ($total_options as $totalValues): ?>
                            <div class="field_spacing">
                                <?php if ($label): ?>
                                    <?php echo form_label('<span class="required">*</span>Options:', 'answers'); ?>
                                <?php else: ?>
                                    <?php echo form_label(); ?>
                                <?php endif; ?>
                                <span>
                                    <?php echo form_radio(array('id' => '', 'name' => 'answers[]', 'value' => (isset($totalValues['option_name'])) ? $totalValues['option_name'] : '', 'checked' => set_radio('answers', $totalValues['option_name'], (isset($totalValues['id']) && $totalValues['id'] == $Question->answer) ? TRUE : FALSE))); ?>
                                    <?php echo form_input(array('onblur' => 'addValueToRadio(this,$(this).val())', 'id' => '', 'name' => 'ans_text[]', 'value' => set_value('question', (isset($totalValues['option_name'])) ? $totalValues['option_name'] : ''))); ?>
                                </span>
                            </div>
                            <?php $label = FALSE; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
        $(".datetime").datetimepicker({
            showSecond: true,
            timeFormat: 'hh:mm:ss tt',
            ampm: true
        });
        $('body').find('#ui-datepicker-div').wrap('<div class="smoothness"></div>');
    });
    function addNewField() {
        var newHtml = '<div class="field_spacing"><label></label><span>' + '<?php echo form_radio(array('id' => '', 'name' => 'answers[]', 'value' => '', 'checked' => set_radio('answers'))); ?>' +
                '<?php echo form_input(array('onblur' => 'addValueToRadio(this,$(this).val())', 'id' => '', 'name' => 'ans_text[]', 'value' => set_value('question', (isset($User->question)) ? $User->question : ''))); ?>' +
                '</span></div>';

        $('#new_row').append(newHtml);
    }

    function addValueToRadio(obj, textval) {
        $(obj).prev('input[type=radio]').val(textval)
    }
</script>
<?php js_end(); ?>