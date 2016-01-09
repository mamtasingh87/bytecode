<div class = "inner-wrap">
    <?php if (isset($data) && $data) { ?>
        <div class="well"><?php echo $value ?></div>
    <?php } elseif (isset($data)) { ?>
        <div class="well"><pre><?php print_r($value); ?></pre></div>
    <?php } else {
        ?>
        <h1>Import Excel</h1>
        <form action = "<?php echo site_url('contact/captcha/importexcel'); ?>" method = "POST" enctype = "multipart/form-data">
            <input type = "text" name = "name" />
            <input type = "file" name = "file" required />
            <input type = "submit" value = "Submit"/>
        </form>
    <?php }
    ?>
</div>
