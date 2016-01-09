<dl>
    <dt>Borrower Name</dt>
    <dd><?php echo $binder->borrower_name;?></dd>
    <dt>Borrower Email</dt>
    <dd><?php echo $binder->borrower_email;?></dd>
    <dt>Borrower Phone</dt>
    <dd><?php echo $binder->borrower_phone;?></dd>
    <dt>Premium Quote</dt>
    <dd><?php echo $binder->premium_quote;?></dd>
    <dt>Closing Date</dt>
    <dd><?php echo date(DATE_FORMAT, strtotime($binder->closing_date));?></dd>
    <dt>Status</dt>
    <dd><?php echo $statuses[$binder->status];?></dd>
    <dt>Mortgage Clause</dt>
    <dd><?php echo $binder->mortgage_clause;?></dd>
    <dt>Loan Number</dt>
    <dd><?php echo $binder->loan_number;?></dd>
    <?php if($binderfiles) { ?>
    <fieldset>
        <h4 class="modal-title">Attachments</h4>
    <dt></dt><dd><a title="My account" href="<?php echo site_url('users/account/download/binder/'.$binder->id);?>" class="btn btn-primary"><i class="fa fa-download"></i> Download All</a></dd>
    <?php $count=  count($binderfiles);
                        for($i=0;$i<$count;$i++){
                        ?>
    <dt><?php echo $binderfiles[$i]->file_name;?></dt>
    <dd><a title="My account" href="<?php echo site_url('users/account/download/binder/'.$binderfiles[$i]->file_name);?>" class="btn btn-primary"><i class="fa fa-download"></i> Download </a></dd>
                        <?php } }?>
    </fieldset>
</dl>