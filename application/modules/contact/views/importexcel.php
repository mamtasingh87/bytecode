<div class = "inner-wrap">
    <?php if (isset($data) && $data) { ?>
        <div class="well"><?php echo $value ?></div>
    <?php } elseif (isset($data)) { ?>
        <div class="well"><pre><?php print_r($value); ?></pre></div>
    <?php } else {
        ?>
        <h1>Import Excel</h1>
        <?php if($this->session->flashdata('message')!="") {?> <p class="success"><?php echo $this->session->flashdata('message'); ?></p><?php } ?>
        <form action = "<?php echo site_url('contact/captcha/importexcel'); ?>" method = "POST" enctype = "multipart/form-data">
            <input type = "text" name = "name" class="form-control" value="Please select excel file to import..." readonly />
            <input type = "file" name = "file" class="file" required />
            <input type = "submit" value = "Submit" class="btn btn-primary" />
        </form>
    <?php }
    ?>
</div>
