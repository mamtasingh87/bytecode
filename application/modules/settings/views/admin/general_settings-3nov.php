<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/setting.png'); ?>"> General Settings</h1>

        <div class="buttons">
            <a class="button" href="#" onClick="$('#settings_form').submit();"><span>Save</span></a>
        </div>
    </div>
    <div class="content">

        <?php echo form_open(null, 'id="settings_form"'); ?>
        <div class="tabs">
            <ul class="htabs">
                <li><a href="#general-tab">General</a></li>
<!--                <li><a href="#users-tab">Users</a></li>-->
                <li><a href="#analytics-tab">Analytics</a></li>
            </ul>
            <!-- General Tab -->
            <div id="general-tab">
                <div class="form">
                    <div>
                        <?php echo form_label('<span class="required">*</span> Site Name:', 'sitename'); ?>
                        <?php echo form_input(array('name' => 'site_name', 'id' => 'sitename', 'value' => set_value('site_name', isset($Settings->site_name->value) ? $Settings->site_name->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Notification Email :', 'notification_email'); ?>
                        <?php echo form_input(array('name' => 'notification_email', 'id' => 'notification_email', 'value' => set_value('notification_email', isset($Settings->notification_email->value) ? $Settings->notification_email->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class=""></span> Notification Email 2:', 'notification_email2'); ?>
                        <?php echo form_input(array('name' => 'notification_email2', 'id' => 'notification_email2', 'value' => set_value('notification_email2', isset($Settings->notification_email2->value) ? $Settings->notification_email2->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Site Homepage:', 'site_homepage'); ?>
                        <?php  echo form_dropdown('content[site_homepage]', option_array_value($Entries, 'id', 'title'), set_value('site_homepage', isset($Settings->site_homepage->value) ? $Settings->site_homepage->value : '')); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Custom 404:', 'custom_404'); ?>
                        <?php  echo form_dropdown('content[custom_404]', option_array_value($Entries, 'id', 'title'), set_value('custom_404', isset($Settings->custom_404->value) ? $Settings->custom_404->value : '')); ?>
                    </div>
<!--                    <div>
                        <?php echo form_label('<span class="required">*</span> Theme:', 'theme'); ?>
                        <?php  echo form_dropdown('theme', $themes, set_value('theme', isset($Settings->theme->value) ? $Settings->theme->value : ''), 'id="theme"'); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Default Layout:', 'layout'); ?>
                        <?php  echo form_dropdown('layout', $layouts, set_value('layout', isset($Settings->layout->value) ? $Settings->layout->value : ''), 'id="theme_layout"'); ?>
                        <span id="layout_ex" class="ex"></span>
                    </div>
                    <div>
                        <?php echo form_label('Content Editor\'s Stylesheet:<span class="help">Enables you to specify a CSS file to extend CKEidtor\'s and TinyMCE\'s default theme and provide custom classes for the styles dropdown.</span>', 'editor_stylesheet'); ?>
                        <span id="editor_stylesheet_path"><?php echo base_url('themes/' . $this->settings->theme) . '/'; ?></span> <?php echo form_input(array('name' => 'editor_stylesheet', 'id' => 'editor_stylesheet', 'value' => set_value('editor_stylesheet', isset($Settings->editor_stylesheet->value) ? $Settings->editor_stylesheet->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Admin Toolbar:', 'enable_admin_toolbar'); ?>
                        <span>
                            <label><?php echo form_radio(array('name' => 'enable_admin_toolbar', 'value' => '1', 'checked' => set_radio('enable_admin_toolbar', '1', ( ! empty($Settings->enable_admin_toolbar->value)) ? TRUE : FALSE))); ?> Yes</label>
                            <label><?php echo form_radio(array('name' => 'enable_admin_toolbar', 'value' => '0', 'checked' => set_radio('enable_admin_toolbar', '0', (empty($Settings->enable_admin_toolbar->value)) ? TRUE : FALSE))); ?> No</label>
                        </span>
                    </div>-->
                    <div>
                        <?php echo form_label('<span class="required">*</span> Frontend Pagination Count:', 'pagination_count'); ?>
                        <?php echo form_input(array('name' => 'pagination_count', 'id' => 'pagination_count', 'value' => set_value('pagination_count', isset($Settings->pagination_count->value) ? $Settings->pagination_count->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span>Point Earned In Correct Answer:', 'points_earned_trivia_question'); ?>
                        <?php echo form_input(array('name' => 'points_earned_trivia_question', 'id' => 'points_earned_trivia_question', 'value' => set_value('points_earned_trivia_question', isset($Settings->points_earned_trivia_question->value) ? $Settings->points_earned_trivia_question->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Amount Earned In One Point ($):', 'amount_earned_trivia_correct_answer'); ?>
                        <?php echo form_input(array('name' => 'amount_earned_trivia_correct_answer', 'id' => 'amount_earned_trivia_correct_answer', 'value' => set_value('amount_earned_trivia_correct_answer', isset($Settings->amount_earned_trivia_correct_answer->value) ? $Settings->amount_earned_trivia_correct_answer->value : ''))); ?>
                    </div>
                    
                    <div>
                        <?php echo form_label('<span class="required">*</span> Bronze(Points Per Referrals):', 'bronze_points_per_referrals'); ?>
                        <?php echo form_input(array('name' => 'bronze_points_per_referrals', 'id' => 'bronze_points_per_referrals', 'value' => set_value('bronze_points_per_referrals', isset($Settings->bronze_points_per_referrals->value) ? $Settings->bronze_points_per_referrals->value : ''))); ?>
                    </div>
                    
                    <div>
                        <?php echo form_label('<span class="required">*</span> Silver(Points Per Referrals):', 'silver_points_per_referrals'); ?>
                        <?php echo form_input(array('name' => 'silver_points_per_referrals', 'id' => 'silver_points_per_referrals', 'value' => set_value('silver_points_per_referrals', isset($Settings->silver_points_per_referrals->value) ? $Settings->silver_points_per_referrals->value : ''))); ?>
                    </div>
                    
                    <div>
                        <?php echo form_label('<span class="required">*</span> Silver(Referrals In Trailing Months):', 'silver_no_referrals_trailing_months'); ?>
                        <?php echo form_input(array('name' => 'silver_no_referrals_trailing_months', 'id' => 'silver_no_referrals_trailing_months', 'value' => set_value('silver_no_referrals_trailing_months', isset($Settings->silver_no_referrals_trailing_months->value) ? $Settings->silver_no_referrals_trailing_months->value : ''))); ?>
                    </div>
                    
                    <div>
                        <?php echo form_label('<span class="required">*</span> Gold(Points Per Referrals):', 'gold_points_per_referrals'); ?>
                        <?php echo form_input(array('name' => 'gold_points_per_referrals', 'id' => 'gold_points_per_referrals', 'value' => set_value('gold_points_per_referrals', isset($Settings->gold_points_per_referrals->value) ? $Settings->gold_points_per_referrals->value : ''))); ?>
                    </div>
                    
                   <div>
                        <?php echo form_label('<span class="required">*</span> Gold(Referrals In Trailing Months):', 'gold_no_referrals_trailing_months'); ?>
                        <?php echo form_input(array('name' => 'gold_no_referrals_trailing_months', 'id' => 'gold_no_referrals_trailing_months', 'value' => set_value('gold_no_referrals_trailing_months', isset($Settings->gold_no_referrals_trailing_months->value) ? $Settings->gold_no_referrals_trailing_months->value : ''))); ?>
                    </div>
                    
                    <div>
                        <?php echo form_label('<span class="required">*</span> Trialing Months:', 'trialing_months'); ?>
                        <?php echo form_input(array('name' => 'trialing_months', 'id' => 'trialing_months', 'value' => set_value('trialing_months', isset($Settings->trialing_months->value) ? $Settings->trialing_months->value : ''))); ?>
                    </div>

                    <div>
                        <?php echo form_label('<span class="required">*</span> Points On Registration:', 'registration_point'); ?>
                        <?php echo form_input(array('name' => 'registration_point', 'id' => 'registration_point', 'value' => set_value('registration_point', isset($Settings->registration_point->value) ? $Settings->registration_point->value : ''))); ?>
                    </div>
                   <div>
                        <?php echo form_label('<span class="required">*</span> Allow Points On Auto Registration:', 'allow_point_auto_registration'); ?>
                        <span>
                            <label><?php echo form_radio(array('name' => 'allow_point_auto_registration', 'value' => '1', 'checked' => set_radio('allow_point_auto_registration', '1', ( ! empty($Settings->allow_point_auto_registration->value)) ? TRUE : FALSE))); ?> Yes</label>
                            <label><?php echo form_radio(array('name' => 'allow_point_auto_registration', 'value' => '0', 'checked' => set_radio('allow_point_auto_registration', '0', (empty($Settings->allow_point_auto_registration->value)) ? TRUE : FALSE))); ?> No</label>
                        </span>
                    </div>
<div>
                        <?php echo form_label('<span class="required">*</span> Resubmit Survey Link After (How Many Requests):', 'registration_point'); ?>
                        <?php echo form_input(array('name' => 'resubmit_survey_counter', 'id' => 'resubmit_survey_counter', 'value' => set_value('resubmit_survey_counter', isset($Settings->resubmit_survey_counter->value) ? $Settings->resubmit_survey_counter->value : ''))); ?> 
                    </div>

<div>
                        <?php echo form_label('<span class="required">*</span> Survey Link:', 'survey_link'); ?>
                        <?php echo form_input(array('name' => 'survey_link', 'id' => 'survey_link', 'value' => set_value('survey_link', isset($Settings->survey_link->value) ? $Settings->survey_link->value : ''))); ?>
                    </div>
<div>
                        <?php echo form_label('<span class="required">*</span> Minimum Amount For Redeem Points', 'min_amount_redeem'); ?>
                        <?php echo form_input(array('name' => 'min_amount_redeem', 'id' => 'min_amount_redeem', 'value' => set_value('min_amount_redeem', isset($Settings->min_amount_redeem->value) ? $Settings->min_amount_redeem->value : ''))); ?>
                    </div>

<div>
                        <?php echo form_label('<span class="required">*</span> Flash Message:', 'flash_message'); ?>
                        <?php echo form_textarea(array('name' => 'flash_message', 'id' => 'flash_message', 'value' => set_value('flash_message', isset($Settings->flash_message->value) ? $Settings->flash_message->value : ''))); ?>
                    </div>


                    
                    
                    <?php if ($this->Group_session->type == SUPER_ADMIN): ?>
<!--                    <div>
                        <?php echo form_label('<span class="required">*</span> Enable Profiler:', 'enable_profiler'); ?>
                        <span>
                            <label><?php echo form_radio(array('name' => 'enable_profiler', 'value' => '1', 'checked' => set_radio('enable_profiler', '1', ( ! empty($Settings->enable_profiler->value)) ? TRUE : FALSE))); ?> Yes</label>
                            <label><?php echo form_radio(array('name' => 'enable_profiler', 'value' => '0', 'checked' => set_radio('enable_profiler', '0', (empty($Settings->enable_profiler->value)) ? TRUE : FALSE))); ?> No</label>
                        </span>
                    </div>-->
                    <?php endif; ?>
                    <?php if ($this->Group_session->type == SUPER_ADMIN): ?>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Suspend Site:', 'suspend'); ?>
                        <span>
                            <label><?php echo form_radio(array('name' => 'suspend', 'value' => '1', 'checked' => set_radio('suspend', '1', ( ! empty($Settings->suspend->value)) ? TRUE : FALSE))); ?> Yes</label>
                            <label><?php echo form_radio(array('name' => 'suspend', 'value' => '0', 'checked' => set_radio('suspend', '0', (empty($Settings->suspend->value)) ? TRUE : FALSE))); ?> No</label>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Users Tab -->
<!--            <div id="users-tab">
                <div class="form">
                    <div>
                        <?php echo form_label('<span class="required">*</span> Default User Group:', 'default_group'); ?>
                        <?php  echo form_dropdown('users[default_group]', option_array_value($Groups, 'id', 'name'), set_value('default_group', isset($Settings->default_group->value) ? $Settings->default_group->value : '')); ?>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> User Registration:', 'enable_registration'); ?>
                        <span>
                            <label><?php echo form_radio(array('name' => 'users[enable_registration]', 'value' => '1', 'checked' => set_radio('enable_registration', '1', ( ! empty($Settings->enable_registration->value)) ? TRUE : FALSE))); ?> Enabled</label>
                            <label><?php echo form_radio(array('name' => 'users[enable_registration]', 'value' => '0', 'checked' => set_radio('enable_registration', '0', (empty($Settings->enable_registration->value)) ? TRUE : FALSE))); ?> Disabled</label>
                        </span>
                    </div>
                    <div>
                        <?php echo form_label('<span class="required">*</span> Require Email Activation:', 'email_activation'); ?>
                        <span>
                            <label><?php echo form_radio(array('name' => 'users[email_activation]', 'value' => '1', 'checked' => set_radio('email_activation', '1', ( ! empty($Settings->email_activation->value)) ? TRUE : FALSE))); ?> Enabled</label>
                            <label><?php echo form_radio(array('name' => 'users[email_activation]', 'value' => '0', 'checked' => set_radio('email_activation', '0', (empty($Settings->email_activation->value)) ? TRUE : FALSE))); ?> Disabled</label>
                        </span>
                    </div>
                </div>
            </div>-->
            <!-- Analytics Tab -->
            <div id="analytics-tab">
                <div class="form">
                    <div>
                        <?php echo form_label('GA Tracking Code:', 'ga_account_id'); ?>
                        <?php echo form_input(array('name' => 'ga_account_id', 'id' => 'ga_account_id', 'value' => set_value('site_name', isset($Settings->ga_account_id->value) ? $Settings->ga_account_id->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('GA Email:', 'ga_email'); ?>
                        <?php echo form_input(array('name' => 'ga_email', 'id' => 'ga_email', 'value' => set_value('ga_email', isset($Settings->ga_email->value) ? $Settings->ga_email->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('GA Password:', 'ga_password'); ?>
                        <?php echo form_password(array('name' => 'ga_password', 'id' => 'ga_password', 'value' => set_value('ga_password', isset($Settings->ga_password->value) ? $Settings->ga_password->value : ''))); ?>
                    </div>
                    <div>
                        <?php echo form_label('GA Profile ID:', 'ga_profile_id'); ?>
                        <?php echo form_input(array('name' => 'ga_profile_id', 'id' => 'ga_profile_id', 'value' => set_value('ga_profile_id', isset($Settings->ga_profile_id->value) ? $Settings->ga_profile_id->value : ''))); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>
</div>

<?php js_start(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $( ".tabs" ).tabs();

        $('#theme').change( function() {

            $('#theme_layout').html('');
            $('#layout_ex').html('Loading Layouts...');

            $.post('<?php echo site_url(ADMIN_PATH . '/settings/general-settings/theme-ajax'); ?>', {theme: $('#theme').val()}, function(response) {
                if (response.status == 'OK')
                {
                    $.each(response.layouts, function(i , val) {
                        $('#theme_layout').append('<option value="' + val + '">' + val + '</option>');
                    });
                    $('#layout_ex').html('');
                }
                else
                {
                    $('#layout_ex').html(response.message);
                }
            }, 'json');

            $('#editor_stylesheet_path').html('<?php echo base_url('themes/') . '/'; ?>' + $('#theme').val() + '/');
        });
    });
</script>
<?php js_end(); ?>