 <div class="inner-wrap">       
<div class="contact-form">
        <div class="row">
           
        <div class="col-sm-6">
            <?php if($this->session->flashdata('message')!="") {?> <p class="success"><?php echo $this->session->flashdata('message'); ?></p><?php } ?>
<form<?php echo (($anchor) ? ' action="' . current_url() . $anchor . '"' : '')  . ' method="post"' . (($id) ? ' id="' . $id . '"' : '') . (($class) ? ' class="' . $class . '"' : ''); ?>>
    <div class="form-group">
    <?php if ($content): ?>
        <?php echo $content; ?>
    <?php else: ?>
        <div class="form-group">
            <label for="name">Name:</label>
            <span class="input-icon icon-right"><input class="form-control" type="text" name="name" id="name" value="<?php echo set_value('name');?>" /></span>
            <?php echo form_error('name');?>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <span class="input-icon icon-right"><input class="form-control" type="text" name="email" id="email" value="<?php echo set_value('email');?>" /></span>
            <?php echo form_error('email');?>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <span class="input-icon icon-right"><input class="form-control" type="text" name="phone" id="phone" value="<?php echo set_value('phone');?>" /></span>
            <?php echo form_error('phone');?>
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <span class="input-icon icon-right"><textarea name="message" id="message" class="form-control"><?php echo set_value('message');?></textarea></span>
            <?php echo form_error('message');?>
        </div>

        <?php if ($captcha): ?>
            <div class="form-group">
                <span>
                    <label for="captcha">Please input the characters below:</label><br />
                    <img class="captcha_image" src="<?php echo site_url('contact/captcha'); ?>" /><br />
                    <span class="input-icon icon-right"><input id="captcha" class="captcha_input" type="text" name="captcha_input" /></span>
                </span>
            </div>
        <?php endif; ?>

        <div class="button-set">
            <label for="submit"></label>
            <input type="submit" id="submit" name="submit"  class="btn btn-primary"value="Send" />
        </div>
    <?php endif; ?>

    <div style="display: none;">
        <input type="text" name="spam_check" value="" /> 
        <?php if ($id): ?>
            <input type="hidden" name="form_id" value="<?php echo $id; ?>" />
        <?php endif; ?>
    </div>
    </div>
</form>
        </div>
            
            <div class="col-sm-6">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    <div class="info-in">
                    <span class="icon addrse-icon"><i class="fa fa-map-marker"></i></span>
                    <span>2005 Vista Parkway, Suite 200 West Palm Beach, FL 33411</span>
                    </div>
                    <div class="info-in">
                    <span class="icon phone-icon"><i class="fa fa-phone-square"></i></span>
                    <span>Phone: 866.568.5522</span>	
                    </div>
                    <div class="info-in">
                    <span class="icon email-icon"><i class="fa fa-envelope"></i></i></span>
                    <span><a  href="#">quoteSlash@InsuranceExpress.com</a></span>
                    </div>
                </div>  
            </div>    
            
        </div>
</div>
</div>
