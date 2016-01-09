<?php
$email = array(
                'id'   => 'email',
                'class' => 'form-control',
                'name' => 'email',
                'value' => set_value('email', (isset($email)) ? $email : ''),
            );

$first_name = array(
                'id'   => 'first_name',
                'class' => 'form-control',
                'name' => 'first_name',
                'value' => set_value('first_name', (isset($first_name)) ? $first_name : ''),
            );

$last_name = array(
                'id'   => 'last_name',
                'class' => 'form-control',
                'name' => 'last_name',
                'value' => set_value('last_name', (isset($last_name)) ? $last_name : ''),
            );

$phone = array(
                'id'   => 'phone',
                'class' => 'form-control',
                'name' => 'phone',
                'value' => set_value('phone', (isset($phone)) ? $phone : ''),
            );

$address = array(
                'id'   => 'address',
                'class' => 'form-control',
                'name' => 'address',
                'value' => set_value('address', (isset($address)) ? $address : ''),
            );

$city = array(
                'id'   => 'city',
                'class' => 'form-control',
                'name' => 'city',
                'value' => set_value('city', (isset($city)) ? $city : ''),
            );


$zip = array(
                'id'   => 'zip',
                'class' => 'form-control',
                'name' => 'zip',
                'value' => set_value('zip', (isset($zip)) ? $zip : ''),
            );

?>

<?php echo form_open(); ?>
    <div>
        <div class="form">
                <h2 class="sub-inner-head">Profile Info</h2>
            <fieldset>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Email:', 'email'); ?>
                            <span class="input-icon"><?php echo form_input($email); ?>
                            <?php echo form_error('email'); ?>
                            </span>
                            
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> First Name:', 'first_name'); ?>
                            <span class="input-icon"><?php echo form_input($first_name); ?>
                            <?php echo form_error('first_name'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Last Name:', 'last_name'); ?>
                            <span class="input-icon"><?php echo form_input($last_name); ?>
                            <?php echo form_error('last_name'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Phone:', 'phone'); ?>
                            <span class="input-icon"><?php echo form_input($phone); ?>
                            <?php echo form_error('phone'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                    <?php echo form_label('<span class="required">*</span> Address:', 'address'); ?>
                    <span class="input-icon"><?php echo form_input($address); ?>
                    <?php echo form_error('address'); ?>
                    </span>
                </div>
                    </div>
                </div>
                                               
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                        <?php echo form_label('<span class="required">*</span> City:', 'city'); ?>
                        <span class="input-icon"><?php echo form_input($city); ?>
                        <?php echo form_error('city'); ?>
                        </span>
                    </div>
                    </div>
<!--                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php // echo form_label('Trivia Question Categories:', 'question_categories'); ?>
                            <select class="form-control select2" name="question_categories[]" multiple="multiple" data-placeholder="Select Categories">
                                <?php // if($categories) {
//                                    foreach($categories as $key => $val){
                                ?>
                                <option value="<?php // echo $key;?>" <?php // if(isset($user_categories) && in_array($key,$user_categories)) { echo 'selected="selected"';}?>><?php // echo $val;?></option>
                                 <?php  
//                                    }
//                                  } 
                                ?>
                             </select>
                            <?php // echo form_error('question_categories'); ?>
                        </div>
                    </div>-->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> State:', 'state'); ?>
                            <span class="select-span"><?php echo form_dropdown('state', $states, set_value('state', (isset($user_state)) ? $user_state : ''),  ('class="form-control"'), ('id="state"')); ?>
                            <?php echo form_error('state'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?php echo form_label('<span class="required">*</span> Zip:', 'zip'); ?>
                            <span class="input-icon"><?php echo form_input($zip); ?>
                            <?php echo form_error('zip'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>&nbsp;</label>
                        <div class="text-left">
                <input class="submit btn btn-primary" type="submit" value="Save" />
            </div>
                    </div>
                </div>
                
            </fieldset>
                       
            

        </div>
    </div>
    <div class="clear"></div>

    <?php echo form_close(); ?>
    <script type="text/javascript">
        $("#email").prop("readonly", true);
    </script>